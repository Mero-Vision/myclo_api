<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WishListResource extends JsonResource
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
                'product'=>new ProductResource($this->whenLoaded('products')),
                'product_varient'=>new ProductVarientResource($this->whenLoaded('productVarients'))
        ];
    }
}
