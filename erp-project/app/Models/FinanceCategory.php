<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinanceCategory extends Model
{
    use HasFactory;

    /**
     * คอมเมนต์: กำหนดฟิลด์ที่สามารถกรอกข้อมูลได้ (Mass Assignable)
     */
    protected $fillable = ['name', 'type'];

    /**
     * คอมเมนต์: สร้างความสัมพันธ์ว่าหนึ่งหมวดหมู่ สามารถมีได้หลายรายการ (transactions)
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(FinancialTransaction::class);
    }
}