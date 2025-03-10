<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryChargeResource extends JsonResource
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
            'district_name' => $this->district_name,
            'cash_on_delivery' => $this->cash_on_delivery,
            'cost_0_1kg' => $this->cost_0_1kg,
            'cost_1_2kg' => $this->cost_1_2kg,
            'cost_2_3kg' => $this->cost_2_3kg,
            'cost_3_5kg' => $this->cost_3_5kg,
            'cost_5_10kg' => $this->cost_5_10kg,
            'cost_above_10kg' => $this->cost_above_10kg,
            'created_at' => $this->created_at
        ];
    }
}
