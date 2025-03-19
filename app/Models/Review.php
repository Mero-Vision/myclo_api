<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends BaseModel
{
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::saved(function ($review) {
            $review->product->updateReviewStats();
        });
    }
}