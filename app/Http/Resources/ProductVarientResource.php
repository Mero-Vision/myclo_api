<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVarientResource extends JsonResource
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
                'product_id'=>$this->product_id,
                'size'=>$this->size,
                'color'=>$this->color,
                'selling_price'=>$this->selling_price,
                'cross_price'=>$this->cross_price,
                'unit_price'=>$this->unit_price,
                'stock_quantity'=>$this->stock_quantity,
                'sku'=>$this->sku,
                'created_at'=>$this->created_at,
                'updated_at'=>$this->updated_at,
                'product_varient_images'=>ProductVarientImageResource::collection($this->whenLoaded('productVarientImages'))


        ];
    }
}
