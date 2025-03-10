<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends BaseModel
{


    //Order Status
    const PROCESSING='processing';
    const DELIVERED='delivered';
    const CANCELLED='cancelled';
    
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function shippingDetails(){
        return $this->belongsTo(ShippingDetail::class,'shipping_detail_id');
    }

    public function orderItems(){
        return $this->hasMany(OrderItem::class,'order_id');
    }
}