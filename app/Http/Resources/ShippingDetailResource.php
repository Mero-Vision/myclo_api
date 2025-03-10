<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShippingDetailResource extends JsonResource
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
            'recipient_name'=>$this->recipient_name,
            'contact_no'=>$this->contact_no,
            'email'=>$this->email,
            'region'=>$this->region,
            'district_city'=>$this->district_city,
            'address'=>$this->address,
            'landmark'=>$this->landmark,
            'is_default'=>$this->is_default,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at
        ];
    }
}
