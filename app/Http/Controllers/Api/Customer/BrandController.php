<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {

        $pagination_limit = request()->query('pagination_limit');
        $search_keyword = request()->query('search_keyword');


        $barnds = Brand::when($search_keyword, function ($query) use ($search_keyword) {
            $query->where('name', 'like', '%' . $search_keyword . '%');
        })->latest();

        $pagination = $pagination_limit ? $barnds->paginate($pagination_limit) : $barnds->get();

        return BrandResource::collection($pagination);
    }

    public function show($slug)
    {
        $pagination_limit = request()->query('pagination_limit');
        $search_keyword = request()->query('search_keyword');

        $brand = Brand::with(
            'products',
            'products.productImages',
            'products.productVarients',
            'products.productVarients.productVarientImages'
        )->where('slug', $slug)
            ->when($search_keyword, function ($query) use ($search_keyword) {
                $query->where('name', 'like', '%' . $search_keyword . '%');
            })
            ->firstOrFail();

        return new BrandResource($brand);
    }
}
