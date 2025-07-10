<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'quantity',
        'price',
        'total',
    ];

    // กำหนดความสัมพันธ์: Item หนึ่งรายการเป็นของ Purchase Order หนึ่งใบ
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    // กำหนดความสัมพันธ์: Item หนึ่งรายการเป็นของ Product หนึ่งชิ้น
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}