<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderStatusUpdateRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
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

    public function updateStatus(OrderStatusUpdateRequest $request, string $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return responseError("Order ID Not Found", 500);
        }

        try {
            $order = DB::transaction(function () use ($request, $order) {

                if ($request->status == "processing") {

                    $order->update([
                        'order_status' => Order::PROCESSING
                    ]);
                } elseif ($request->status == "delivered") {
                    $order->update([
                        'order_status' => Order::DELIVERED
                    ]);
                } elseif ($request->status == "cancelled") {
                    $order->update([
                        'order_status' => Order::CANCELLED
                    ]);
                }

                return $order;
            });
            if ($order) {
                return responseSuccess(new OrderResource($order), 200, 'Order Status Updated Successfully!');
            }
        } catch (\Exception $e) {
            return responseError($e->getMessage(), 500);
        }
    }
}