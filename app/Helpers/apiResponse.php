<?php

use App\Models\Order;

function responseSuccess($data = null, $status = 200, $message = null)
{
    $response = [];
    if ($data)
        $response["data"] = $data;
    if ($message)
        $response["message"] = $message;
    return response()->json($response, $status);
}


function responseError($message = null, $status = 500)
{
    $response["message"] = $message;
    return response()->json($response, $status);
}


function generateUniqueOrderNumber() {
   
    $date = date('Ymd');

    
    $latestOrder = Order::where('order_number', 'like', 'ORD' . $date . '%')
                        ->orderBy('order_number', 'desc')
                        ->first();

    
    if ($latestOrder) {
        $lastIncrement = (int) substr($latestOrder->order_number, -2); 
        $increment = $lastIncrement + 1;
    } else {
        $increment = 1; 
    }

    $increment = str_pad($increment, 2, '0', STR_PAD_LEFT);

    $orderNumber = 'ORD' . $date . $increment;

    return $orderNumber;
}




