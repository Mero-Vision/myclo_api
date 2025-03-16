<?php

namespace App\Http\Resources;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $discountPercent = 0;
        if ($this->cross_price > 0 && $this->selling_price < $this->cross_price) {
            $discountPercent = round((($this->cross_price - $this->selling_price) / $this->cross_price) * 100, 2);
        }

        $wishlist = null;
        if (Auth::check()) {
            $wishlist = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $this->id)
                ->first(); // Check if the wishlist entry exists
        }

        return [

            'id'=>$this->id,
            'category_id'=>$this->category_id,
            'brand_id'=>$this->brand_id,
            'name'=>$this->name,
            'slug'=>$this->slug,
            'description'=>$this->description,
            'selling_price'=>$this->selling_price,
            'cross_price'=>$this->cross_price,
            'discount_percent' => $discountPercent,
            'unit_price'=>$this->unit_price,
            'stock_quantity'=>$this->stock_quantity,
            'sku'=>$this->sku,
            'allow_negative_stock'=>$this->allow_negative_stock,
            'product_weight'=>$this->product_weight,
            'status'=>$this->status,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
            'category'=>new CategoryResource($this->whenLoaded('category')),
            'brand'=>new BrandResource($this->whenLoaded('brands')),
            'product_images'=>ProductImageResource::collection($this->whenLoaded('productImages')),
            'wishlist' => $wishlist ,


        ];
    }
}