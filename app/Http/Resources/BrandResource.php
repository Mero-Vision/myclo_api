<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'brand_image' => $this->getFirstMediaUrl('brand_image') ?? null,
            'created_at' => $this->created_at,
            'products'=>ProductResource::collection($this->whenLoaded('products'))
        ];
    }
}
