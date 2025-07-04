<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Category extends Model
{
    use HasFactory;
    use SoftDeletes;
    // กำหนดฟิลด์ที่อนุญาตให้กรอกข้อมูลได้ผ่าน Mass Assignment (เช่น การใช้คำสั่ง create)
    protected $fillable = ['name'];

    // กำหนดความสัมพันธ์ว่า 1 Category มีได้หลาย Products (One-to-Many)
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
