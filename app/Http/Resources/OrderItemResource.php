<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'user_id'=>$this->user_id,
            'order_id'=>$this->order_id,
            'product_id'=>$this->product_id,
            'product_variant_id'=>$this->product_variant_id,
            'quantity'=>$this->quantity,
            'price'=>$this->price,
            'subtotal'=>$this->subtotal,
            'product'=>new ProductResource($this->whenLoaded('products')),
            'product_varient'=>new ProductVarientResource($this->whenLoaded('productVarients'))
        ];
    }
}
