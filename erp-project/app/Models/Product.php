<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

    class Product extends Model

{
    use HasFactory;
    use SoftDeletes;

    // กำหนดฟิลด์ที่อนุญาตให้กรอกข้อมูลได้ผ่าน Mass Assignment
    protected $fillable = ['sku', 'name', 'description', 'selling_price', 'quantity', 'category_id', 'image', 'min_stock_level'];

    // กำหนดความสัมพันธ์ว่า 1 Product เป็นของ 1 Category (Inverse of One-to-Many)
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    // ความสัมพันธ์: สินค้าหนึ่งชิ้นสามารถอยู่ในรายการสั่งขายได้หลายรายการ
    public function salesOrderItems(): HasMany
    {
        return $this->hasMany(SalesOrderItem::class);
    }
}
