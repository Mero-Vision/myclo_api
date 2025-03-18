<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\CartStoreRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $pagination_limit = request()->query('pagination_limit');

        $carts = Cart::where('user_id',Auth::user()->id)->with('products', 'products.productImages', 'productVarients', 'productVarients.productVarientImages')->latest();

        $pagination = $pagination_limit ? $carts->paginate($pagination_limit) : $carts->get();

        return CartResource::collection($pagination);
    }

    public function cartQuantityUpdate(Request $request, string $id)
    {
        $cart = Cart::find($id);
        if (!$cart) {
            return responseError('Cart Detail Not Found', 500);
        }
        try {
            $cart = DB::transaction(function () use ($cart,$request) {
                
                $cart->update([
                    'quantity' => $request->quantity,
                   
                ]);

                return $cart;
            });

            if ($cart) {
                return responseSuccess($cart, 200, 'Quantity Updated Successfully!');
            }
        } catch (\Exception $e) {
            return responseError($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CartStoreRequest $request)
    {
        try {
            $cart = DB::transaction(function () use ($request) {
                $cart = Cart::firstOrNew([
                    'user_id' => Auth::user()->id,
                    'product_id' => $request->product_id,
                    'product_varient_id' => $request->product_varient_id,
                ]);
                
                // If the cart item already exists, add the new quantity to the existing quantity
                if ($cart->exists) {
                    $cart->quantity += $request->quantity;
                } else {
                    // If it doesn't exist, set the quantity to the new quantity
                    $cart->quantity = $request->quantity;
                }
                
                $cart->price = $request->price;
                $cart->save();

                return $cart;
            });
            if ($cart) {
                $cart->load('products', 'products.productImages', 'productVarients', 'productVarients.productVarientImages');
                return responseSuccess(new CartResource($cart), 200, 'Cart Has Been Created Successfully!');
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
        $cart = Cart::find($id);
        if (!$cart) {
            return responseError('Cart Not Found', 500);
        }
        try {
            $cart = DB::transaction(function () use ($cart) {

                $cart->delete();

                return $cart;
            });

            if ($cart) {
                return responseSuccess($cart, 200, 'Cart Deleted Successfully!');
            }
        } catch (\Exception $e) {
            return responseError($e->getMessage(), 500);
        }
    }
}