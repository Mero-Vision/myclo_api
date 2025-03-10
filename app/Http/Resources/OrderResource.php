<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'order_number'=>$this->order_number,
            'shipping_detail_id'=>$this->shipping_detail_id,
            'subtotal'=>$this->subtotal,
            'delivery_charge'=>$this->delivery_charge,
            'discount'=>$this->discount,
            'tax'=>$this->tax,
            'total_amount'=>$this->total_amount,
            'note'=>$this->note,
            'order_status'=>$this->order_status,
            'customer'=>new CustomerResource($this->whenLoaded('user')),
            'shipping_details'=>new ShippingDetailResource($this->whenLoaded('shippingDetails')),
            'order_items'=>OrderItemResource::collection($this->whenLoaded('orderItems'))
        ];
    }
}