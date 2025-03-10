<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerDashboardResource extends JsonResource
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
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d h:i a') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d h:i a') : null,
            'orders_count' => $this->orders_count ?? $this->orders()->count(),
            'carts_count' => $this->carts_count ?? $this->carts()->count(),
            'wishlists_count' => $this->wishlists_count ?? $this->wishlists()->count(),


           
        ];
    }
}
