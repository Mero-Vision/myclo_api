<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentOptionResource;
use App\Models\PaymentOption;
use Illuminate\Http\Request;

class PaymentOptionController extends Controller
{
    public function index()
    {

        $pagination_limit = request()->query('pagination_limit');
        $search_keyword = request()->query('search_keyword');


        $paymentOptions = PaymentOption::latest();

        $pagination = $pagination_limit ? $paymentOptions->paginate($pagination_limit) : $paymentOptions->get();

        return PaymentOptionResource::collection($pagination);
    }
}