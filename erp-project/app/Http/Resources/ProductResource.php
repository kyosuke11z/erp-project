<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // กำหนดโครงสร้าง JSON ที่ต้องการส่งออกไป
        // เราสามารถเลือกเฉพาะฟิลด์ที่จำเป็นและเปลี่ยนชื่อ key ได้ตามต้องการ
        return [
            'id' => $this->id,
            'product_name' => $this->name, // เปลี่ยนชื่อ key จาก name เป็น product_name
            'sku' => $this->sku,
            'price' => (float) $this->price, // แปลงค่าเป็น float เพื่อความถูกต้อง
            'stock' => $this->quantity,
            // โหลดข้อมูล category ที่มีความสัมพันธ์กันอยู่ (ถ้ามี)
            // และเลือกแสดงเฉพาะชื่อของ category
            'category' => $this->whenLoaded('category', fn () => $this->category->name),
        ];
    }
}