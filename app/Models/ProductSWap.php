<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSWap extends BaseModel
{
    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function requesterProduct()
    {
        return $this->belongsTo(Product::class, 'requester_product_id');
    }

    public function ownerProduct()
    {
        return $this->belongsTo(Product::class, 'owner_product_id');
    }
}