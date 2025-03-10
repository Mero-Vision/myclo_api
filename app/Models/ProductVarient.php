<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVarient extends BaseModel
{
    public function productVarientImages(){
        return $this->hasMany(ProductImage::class,'product_varient_id');
    }
}
