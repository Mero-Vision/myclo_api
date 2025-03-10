<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends BaseModel
{
    public function products(){
        return $this->belongsTo(Product::class,'product_id');
    }

    public function productVarients(){
        return $this->belongsTo(ProductVarient::class,'product_varient_id');
    }
}
