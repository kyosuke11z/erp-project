<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order_id' => $this->id,
            'order_number' => $this->order_number,
            'order_date' => $this->order_date,
            'status' => $this->status,
            'total_amount' => (float) $this->total_amount,
            'customer_info' => new CustomerResource($this->whenLoaded('customer')),
            'order_items' => SalesOrderItemResource::collection($this->whenLoaded('items')),
        ];
    }
}