<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVarient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $pagination_limit = request()->query('pagination_limit');
        $search_keyword = request()->query('search_keyword');


        $products = Product::with('productImages', 'productVarients', 'productVarients.productVarientImages')->when($search_keyword, function ($query) use ($search_keyword) {
            $query->where('name', 'like', '%' . $search_keyword . '%');
        })->latest();

        $pagination = $pagination_limit ? $products->paginate($pagination_limit) : $products->get();

        return ProductResource::collection($pagination);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        try {
            $product = DB::transaction(function () use ($request) {
                $product = $this->createProduct($request);

                if ($request->has_varient) {
                    $this->createVariants($request, $product);
                }

                if ($request->hasFile('product_image')) {
                    $this->uploadProductImages($request, $product);
                }

                return $product;
            });

            return responseSuccess(new ProductResource($product), 200, 'Product Has Been Created Successfully!');
        } catch (\Exception $e) {
            return responseError($e->getMessage(), 500);
        }
    }

    protected function createProduct($request)
    {
        return Product::create([
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'created_user_id' => Auth::user()->id,
            'name' => $request->name,
            'description' => $request->description,
            'selling_price' => $request->selling_price,
            'cross_price' => $request->cross_price,
            'unit_price' => $request->unit_price,
            'stock_quantity' => $request->stock_quantity,
            'sku' => $request->sku,
            'allow_negative_stock' => $request->allow_negative_stock,
            'has_varient' => $request->has_varient,
            'status' => $request->status
        ]);
    }

    protected function createVariants($request, $product)
    {
        foreach ($request->varients as $variant) {
            $productVariant = ProductVarient::create([
                'product_id' => $product->id,
                'size' => $variant['size'],
                'color' => $variant['color'],
                'selling_price' => $variant['varient_selling_price'],
                'cross_price' => $variant['varient_cross_price'],
                'unit_price' => $variant['varient_unit_price'],
                'stock_quantity' => $variant['varient_stock_quantity'],
                'sku' => $variant['varient_sku'],
            ]);

            if (isset($variant['images']) && is_array($variant['images'])) {
                $this->uploadVariantImages($variant['images'], $productVariant);
            }
        }
    }

    protected function uploadVariantImages($images, $productVariant)
    {
        foreach ($images as $image) {
            $imageModel = new ProductImage([
                'product_varient_id' => $productVariant->id,
                'image_type' => 'variant',
                'is_primary' => false,
            ]);
            $imageModel->save();
            $imageModel->addMedia($image['url'])->toMediaCollection('product_varient_image');
        }
    }

    protected function uploadProductImages($request, $product)
    {
        foreach ($request->file('product_image') as $productImageFile) {
            $productImage = ProductImage::create([
                'product_id' => $product->id,
                'image_type' => 'product',
                'is_primary' => true,
            ]);
            $productImage->addMedia($productImageFile)->toMediaCollection('product_image');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $product = Product::with('productImages', 'productVarients', 'productVarients.productVarientImages','category')->where('slug', $slug)->first();

        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        try {
            DB::transaction(function () use ($product) {
                // Delete product variants and their images
                if ($product->productVarients()->exists()) {
                    foreach ($product->productVarients as $variant) {
                        // Delete variant images
                        $variant->productVarientImages()->delete();
                        // Delete the variant
                        $variant->delete();
                    }
                }

                // Delete product images
                $product->productImages()->delete();

                // Delete the product
                $product->delete();
            });

            return responseSuccess(null, 200, 'Product Has Been Deleted Successfully!');
        } catch (\Exception $e) {
            return responseError($e->getMessage(), 500);
        }
    }
}
