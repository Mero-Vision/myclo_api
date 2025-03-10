<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\WishList\WishListStoreRequest;
use App\Http\Resources\WishListResource;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $pagination_limit = request()->query('pagination_limit');

        $wishlists = Wishlist::with('products', 'products.productImages', 'productVarients', 'productVarients.productVarientImages')->latest();

        $pagination = $pagination_limit ? $wishlists->paginate($pagination_limit) : $wishlists->get();

        return WishListResource::collection($pagination);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WishListStoreRequest $request)
    {
        try {
            $wishList = DB::transaction(function () use ($request) {
                $wishList = Wishlist::create([
                    'user_id' => Auth::user()->id,
                    'product_id' => $request->product_id,
                    'product_varient_id' => $request->product_varient_id
                ]);

                return $wishList;
            });
            if ($wishList) {
                $wishList->load('products', 'products.productImages', 'productVarients', 'productVarients.productVarientImages');
                return responseSuccess(new WishListResource($wishList), 200, 'Wishlist Has Been Created Successfully!');
            }
        } catch (\Exception $e) {
            return responseError($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $wishList = Wishlist::find($id);
        if (!$wishList) {
            return responseError('Wishlist Not Found', 500);
        }
        try {
            $wishList = DB::transaction(function () use ($wishList) {
                
                $wishList->delete();

                return $wishList;
            });

            if ($wishList) {
                return responseSuccess($wishList, 200, 'Wishlist Deleted Successfully!');
            }
        } catch (\Exception $e) {
            return responseError($e->getMessage(), 500);
        }
    }
}
