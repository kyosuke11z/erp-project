<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesOrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product_details' => new ProductResource($this->whenLoaded('product')),
            'quantity' => (int) $this->quantity,
            'unit_price' => (float) $this->price,
        ];
    }
}