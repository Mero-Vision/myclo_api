<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryCreateRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $pagination_limit = request()->query('pagination_limit');
        $search_keyword = request()->query('search_keyword');


        $categories = Category::when($search_keyword, function ($query) use ($search_keyword) {
            $query->where('name', 'like', '%' . $search_keyword . '%');
        })->latest();

        $pagination = $pagination_limit ? $categories->paginate($pagination_limit) : $categories->get();

        return CategoryResource::collection($pagination);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryCreateRequest $request)
    {
        try {
            $category = DB::transaction(function () use ($request) {
                $category = Category::create([
                    'name' => $request->name,

                ]);
                if ($request->category_image) {
                    $category->addMedia($request->category_image)->toMediaCollection('category_image');
                }
                return $category;
            });
            if ($category) {
                return responseSuccess(new CategoryResource($category), 200, 'Category Has Been Created Successfully!');
            }
        } catch (\Exception $e) {
            return responseError($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return responseError('Category Not Found', 500);
        }
        try {
            $category = DB::transaction(function () use ($category, $request) {

                $category->update([
                    'name' => $request->name,
                ]);

                if ($request->category_image) {
                    $category->clearMediaCollection('category_image');
                    $category->addMedia($request->category_image)->toMediaCollection('category_image');
                }


                return $category;
            });

            if ($category) {
                return responseSuccess($category, 200, 'Category Updated Successfully!');
            }
        } catch (\Exception $e) {
            return responseError($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return responseError('Category Not Found', 500);
        }
        try {
            $category = DB::transaction(function () use ($category) {
                if ($category->hasMedia('category_image')) {
                    $category->clearMediaCollection('category_image');
                }
                $category->delete();

                return $category;
            });

            if ($category) {
                return responseSuccess($category, 200, 'Category Deleted Successfully!');
            }
        } catch (\Exception $e) {
            return responseError($e->getMessage(), 500);
        }
    }
}
