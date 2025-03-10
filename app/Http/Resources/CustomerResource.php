<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'roles' => $this->getRoleNames(),
            'profile_image' => $this->getFirstMediaUrl('profile_image') ?  $this->getFirstMediaUrl('profile_image') : null,
            // 'last_login_at' => $this->last_login_at,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d h:i a') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d h:i a') : null,
            'orders_count' => $this->whenLoaded('orders', function () {
                return $this->orders->count();
            }),
            'orders_count' => $this->orders_count ?? $this->orders()->count(),
            'orders' => OrderResource::collection($this->whenLoaded('orders')),
           
        ];
    }
}
