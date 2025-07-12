<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FinancialTransaction extends Model
{
    use HasFactory;

    /**
     * คอมเมนต์: กำหนดฟิลด์ที่สามารถกรอกข้อมูลได้ (Mass Assignable)
     */
    protected $fillable = [
        'type',
        'transaction_date',
        'amount',
        'description',
        'finance_category_id',
        'related_model_type',
        'related_model_id',
        'user_id',
    ];

    /**
     * คอมเมนต์: กำหนดประเภทข้อมูลของคอลัมน์ เพื่อให้ Laravel จัดการข้อมูลได้ถูกต้อง
     */
    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * คอมเมนต์: ความสัมพันธ์ว่ารายการนี้ "เป็นของ" หมวดหมู่ใด (belongsTo)
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(FinanceCategory::class, 'finance_category_id');
    }

    /**
     * คอมเมนต์: ความสัมพันธ์ว่ารายการนี้ "เป็นของ" ผู้ใช้คนใด (belongsTo)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * คอมเมนต์: ความสัมพันธ์แบบ Polymorphic เพื่อดึงข้อมูลจากโมเดลที่เกี่ยวข้อง
     */
    public function relatedModel(): MorphTo
    {
        return $this->morphTo();
    }
}