<?php

namespace App\Policies;

use App\Models\PurchaseOrder;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PurchaseOrderPolicy
{
    /**
     * Determine whether the user can view the model.
     * ตรวจสอบว่าผู้ใช้สามารถดู/พิมพ์ใบสั่งซื้อได้หรือไม่
     */
    public function view(User $user, PurchaseOrder $purchaseOrder): bool
    {
        // ตรวจสอบว่าผู้ใช้มีสิทธิ์ 'view purchase orders' หรือไม่
        return $user->can('view purchase orders');
    }

    /**
     * Determine whether the user can delete the model.
     * ตรวจสอบว่าผู้ใช้สามารถลบใบสั่งซื้อได้หรือไม่
     */
    public function delete(User $user, PurchaseOrder $purchaseOrder): bool
    {
        // ตรวจสอบว่าผู้ใช้มีสิทธิ์ 'delete purchase orders' หรือไม่
        // และสถานะต้องเป็น 'pending'
        return $user->can('delete purchase orders') && $purchaseOrder->status === 'pending';
    }
}