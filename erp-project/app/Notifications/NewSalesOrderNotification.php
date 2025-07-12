<?php

namespace App\Notifications;

use App\Models\SalesOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewSalesOrderNotification extends Notification
{
    use Queueable;

    protected $salesOrder;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(SalesOrder $salesOrder)
    {
        $this->salesOrder = $salesOrder;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // เราจะใช้การแจ้งเตือนผ่านฐานข้อมูล (ไอคอนกระดิ่ง)
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        // ข้อมูลที่จะถูกเก็บในคอลัมน์ 'data' ของตาราง notifications
        return [
            'sales_order_id' => $this->salesOrder->id,
            'sales_order_number' => $this->salesOrder->order_number,
            'message' => "มีคำสั่งขายใหม่: #{$this->salesOrder->order_number}",
            'url' => route('sales.show', $this->salesOrder->id),
        ];
    }
}