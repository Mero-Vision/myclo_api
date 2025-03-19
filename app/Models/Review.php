<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends BaseModel
{
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::saved(function ($review) {
            $review->product->updateReviewStats();
        });
    }
}