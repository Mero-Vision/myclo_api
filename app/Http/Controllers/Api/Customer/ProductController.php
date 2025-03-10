<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {

        $pagination_limit = request()->query('pagination_limit');
        $search_keyword = request()->query('search_keyword');
        $limit = request()->query('limit');



        $products = Product::with('productImages', 'productVarients', 'productVarients.productVarientImages','category')->when($search_keyword, function ($query) use ($search_keyword) {
            $query->where('name', 'like', '%' . $search_keyword . '%');
        })->when($limit,function($query) use($limit){
                $query->limit($limit);
        })->latest();

        $pagination = $pagination_limit ? $products->paginate($pagination_limit) : $products->get();

        return ProductResource::collection($pagination);
    }

    public function allProducts()
    {

        $pagination_limit = request()->query('pagination_limit');
        $search_keyword = request()->query('search_keyword');


        $products = Product::with('productImages', 'productVarients', 'productVarients.productVarientImages','category')->when($search_keyword, function ($query) use ($search_keyword) {
            $query->where('name', 'like', '%' . $search_keyword . '%');
        })->latest();

        $pagination = $pagination_limit ? $products->paginate($pagination_limit) : $products->get();

        return ProductResource::collection($pagination);
    }
    
    public function show(string $slug)
    {
        $product = Product::with('productImages', 'productVarients', 'productVarients.productVarientImages','category','brands')->where('slug', $slug)->first();

        return new ProductResource($product);
    }
}
