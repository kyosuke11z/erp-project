<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'po_number',
        'supplier_id',
        'order_date',
        'expected_delivery_date',
        'total_amount',
        'status',
        'paid_at',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
    ];

    // กำหนดความสัมพันธ์: Purchase Order หนึ่งใบเป็นของ Supplier หนึ่งราย
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class)->withTrashed();
    }

    // กำหนดความสัมพันธ์: Purchase Order หนึ่งใบมีได้หลาย Items
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
     public function goodsReceipts(): HasMany
    {
        return $this->hasMany(GoodsReceipt::class);
    }
     /**
     * กำหนดความสัมพันธ์แบบ Polymorphic (one-to-many) ไปยัง FinancialTransaction
     * หนึ่ง Purchase Order สามารถมีรายการทางการเงิน (รายจ่าย) ได้หลายรายการ
     */
    public function financialTransactions(): MorphMany
    {
        return $this->morphMany(FinancialTransaction::class, 'related_model');
    }
}