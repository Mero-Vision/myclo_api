<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Brand\BrandCreateRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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


    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandCreateRequest $request)
    {
        try {
            $brand = DB::transaction(function () use ($request) {
                $brand = Brand::create([
                    'name' => $request->name,

                ]);
                if ($request->brand_image) {
                    $brand->addMedia($request->brand_image)->toMediaCollection('brand_image');
                }
                return $brand;
            });
            if ($brand) {
                return responseSuccess(new BrandResource($brand), 200, 'Brand Has Been Created Successfully!');
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
        $brand = Brand::find($id);
        if (!$brand) {
            return responseError('Brand Not Found', 500);
        }
        try {
            $brand = DB::transaction(function () use ($brand, $request) {

                $brand->update([
                    'name' => $request->name,
                ]);

                if ($request->brand_image) {
                    $brand->clearMediaCollection('brand_image');
                    $brand->addMedia($request->brand_image)->toMediaCollection('brand_image');
                }


                return $brand;
            });

            if ($brand) {
                return responseSuccess($brand, 200, 'Brand Updated Successfully!');
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
        $brand = Brand::find($id);
        if (!$brand) {
            return responseError('Brand Not Found', 500);
        }
        try {
            $brand = DB::transaction(function () use ($brand) {
                if ($brand->hasMedia('brand_image')) {
                    $brand->clearMediaCollection('brand_image');
                }
                $brand->delete();

                return $brand;
            });

            if ($brand) {
                return responseSuccess($brand, 200, 'Brand Deleted Successfully!');
            }
        } catch (\Exception $e) {
            return responseError($e->getMessage(), 500);
        }
    }
}
