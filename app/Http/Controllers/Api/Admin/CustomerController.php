<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $pagination_limit = request()->query('pagination_limit');
        $search_keyword = request()->query('search_keyword');

        $customers = User::role('customer')->when($search_keyword, function ($query) use ($search_keyword) {
            $query->where('name', 'like', '%' . $search_keyword . '%');
        })->latest();

        $pagination = $pagination_limit ? $customers->paginate($pagination_limit) : $customers->get();

        return CustomerResource::collection($pagination);
    }

    public function customerOrders($customer_id)
    {


        $customer = User::with(
            'orders',
            'orders.orderItems',
            'orders.orderItems.products',
            'orders.orderItems.products.productImages',
            'orders.orderItems.productVarients',
            'orders.orderItems.productVarients.productVarientImages'
        )->role('customer')->findOrFail($customer_id);

        return new CustomerResource($customer);
    }
}
