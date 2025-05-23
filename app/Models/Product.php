<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends BaseModel
{
    use HasSlug;

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(50);
    }

    public function productVarients(){
        return $this->hasMany(ProductVarient::class,'product_id');
    }

    public function rentalProduct(){
        return $this->hasMany(RentalProduct::class,'product_id');
    }

    public function productImages(){
        return $this->hasMany(ProductImage::class,'product_id');
    }

    public function wishlist(){
        return $this->hasOne(Wishlist::class,'product_id');
    }

    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }

    public function brands(){
        return $this->belongsTo(Brand::class,'brand_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function updateReviewStats()
    {
        $this->review_count = $this->reviews()->count();
        $this->average_rating = $this->reviews()->avg('rating') ?: 0;
        $this->save();
    }
}