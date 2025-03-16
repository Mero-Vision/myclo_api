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


        $products = Product::with('productImages')->when($search_keyword, function ($query) use ($search_keyword) {
            $query->where('name', 'like', '%' . $search_keyword . '%');
        })->latest();

        $pagination = $pagination_limit ? $products->paginate($pagination_limit) : $products->get();

        return ProductResource::collection($pagination);
    }

    /**
     * Store a newly created resource in storage.
     */
    

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $product = Product::with('productImages','category')->where('slug', $slug)->first();

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