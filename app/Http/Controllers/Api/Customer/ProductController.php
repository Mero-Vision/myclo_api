<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVarient;
use App\Models\RentalProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {

        $pagination_limit = request()->query('pagination_limit');
        $search_keyword = request()->query('search_keyword');
        $limit = request()->query('limit');



        $products = Product::with('productImages', 'category')->when($search_keyword, function ($query) use ($search_keyword) {
            $query->where('name', 'like', '%' . $search_keyword . '%');
        })->when($limit, function ($query) use ($limit) {
            $query->limit($limit);
        })->latest();

        $pagination = $pagination_limit ? $products->paginate($pagination_limit) : $products->get();

        return ProductResource::collection($pagination);
    }

    public function myproducts()
    {

        $pagination_limit = request()->query('pagination_limit');
        $search_keyword = request()->query('search_keyword');


        $products = Product::with('productImages','category')->where('client_id',Auth::user()->id)->when($search_keyword, function ($query) use ($search_keyword) {
            $query->where('name', 'like', '%' . $search_keyword . '%');
        })->latest();

        $pagination = $pagination_limit ? $products->paginate($pagination_limit) : $products->get();

        return ProductResource::collection($pagination);
    }

    public function show(string $slug)
    {
        $product = Product::with('productImages','category', 'brands')->where('slug', $slug)->first();

        return new ProductResource($product);
    }

    public function store(ProductStoreRequest $request)
    {
        try {
            $product = DB::transaction(function () use ($request) {
                $product = $this->createProduct($request);

                if ($request->hasFile('product_image')) {
                    $this->uploadProductImages($request, $product);
                }

                if ($request->has('rental_price')) {
                    RentalProduct::create([
                        'product_id' => $product->id,
                        'rental_price' => $request->rental_price,
                        'rental_duration' => $request->rental_duration,
                        'rental_type' => $request->rental_type,
                    ]);
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
            'client_id' => Auth::user()->id,
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
}