<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $pagination_limit = request()->query('pagination_limit');

        $orders = Order::with(
            'user',
            'shippingDetails',
            'orderItems',
            'orderItems.products',
            'orderItems.products.productImages',
            'orderItems',
            'orderItems.productVarients',
            'orderItems.productVarients.productVarientImages'
        )->latest();

        $pagination = $pagination_limit ? $orders->paginate($pagination_limit) : $orders->get();

        return OrderResource::collection($pagination);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            $order = DB::transaction(function () use ($request) {
                $order = Order::create([
                    'user_id' => Auth::user()->id,
                    'order_number' => generateUniqueOrderNumber(),
                    'shipping_detail_id' => $request->shipping_detail_id,
                    'subtotal' => $request->subtotal,
                    'delivery_charge' => $request->delivery_charge,
                    'discount' => $request->discount,
                    'tax' => $request->tax,
                    'total_amount' => $request->total_amount,
                    'note' => $request->note,
                ]);

                if ($request->order_items) {
                    foreach ($request->order_items as $order_item) {
                        OrderItem::create([
                            'user_id' => Auth::user()->id,
                            'order_id' => $order->id,
                            'product_id' => $order_item['product_id'],
                            'product_varient_id' => $order_item['product_varient_id'],
                            'quantity' => $order_item['quantity'],
                            'price' => $order_item['price'],
                            'subtotal' => $order_item['price'] * $order_item['quantity'],
                        ]);

                        $cart = Cart::where('user_id', Auth::user()->id)
                            ->where('product_id', $order_item['product_id'])
                            ->where(function ($query) use ($order_item) {
                                if ($order_item['product_varient_id'] === null) {
                                    $query->whereNull('product_varient_id');
                                } else {
                                    $query->where('product_varient_id', $order_item['product_varient_id']);
                                }
                            })
                            ->first();

                        if ($cart) {
                            $cart->delete();
                        }
                    }
                }

                return $order;
            });
            if ($order) {
                return responseSuccess($order, 200, 'Order Has Been Created Successfully!');
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
    public function destroy(string $id)
    {
        //
    }
}
