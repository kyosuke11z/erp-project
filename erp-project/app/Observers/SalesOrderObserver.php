<?php

namespace App\Observers;

use App\Models\SalesOrder;
use App\Models\User;
use App\Notifications\NewSalesOrderNotification;
use Illuminate\Support\Facades\Notification;

class SalesOrderObserver
{
    /**
     * Handle the SalesOrder "created" event.
     *
     * @param  \App\Models\SalesOrder  $salesOrder
     * @return void
     */
    public function created(SalesOrder $salesOrder)
    {
        // ค้นหาผู้ใช้ทั้งหมดที่มี Role 'Admin' หรือ 'Manager'
        $usersToNotify = User::role(['Admin', 'Manager'])->get();

        // ส่ง Notification ไปยังผู้ใช้กลุ่มนี้
        if ($usersToNotify->isNotEmpty()) {
            Notification::send($usersToNotify, new NewSalesOrderNotification($salesOrder));
        }
    }
}