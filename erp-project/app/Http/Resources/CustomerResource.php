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
            'customer_name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone,
            'full_address' => trim($this->address), // ใช้ trim เพื่อตัดช่องว่างที่ไม่จำเป็นออก
        ];
    }
}