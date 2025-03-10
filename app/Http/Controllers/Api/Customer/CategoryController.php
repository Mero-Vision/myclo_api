<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {

        $pagination_limit = request()->query('pagination_limit');
        $search_keyword = request()->query('search_keyword');
        $limit = request()->query('limit');


        $categories = Category::when($search_keyword, function ($query) use ($search_keyword) {
            $query->where('name', 'like', '%' . $search_keyword . '%');
        })->when($limit, function ($query) use ($limit) {
            $query->limit($limit);
        })->latest();

        $pagination = $pagination_limit ? $categories->paginate($pagination_limit) : $categories->get();

        return CategoryResource::collection($pagination);
    }

    public function show($slug)
    {
        $pagination_limit = request()->query('pagination_limit');
        $search_keyword = request()->query('search_keyword');

        $category = Category::with(
            'products',
            'products.productImages',
            'products.productVarients',
            'products.productVarients.productVarientImages'
        )->where('slug', $slug)
            ->when($search_keyword, function ($query) use ($search_keyword) {
                $query->where('name', 'like', '%' . $search_keyword . '%');
            })
            ->firstOrFail();

        return new CategoryResource($category);
    }
}
