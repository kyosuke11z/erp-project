<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockAlert extends Notification
{
    use Queueable;

    public Product $product;

    /**
     * Create a new notification instance.
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // เราจะเก็บการแจ้งเตือนไว้ในฐานข้อมูล
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'product_id'      => $this->product->id,
            'product_name'    => $this->product->name,
            'current_stock'   => $this->product->quantity, // แก้จาก stock เป็น quantity
            'message'         => "สินค้า '{$this->product->name}' ใกล้จะหมดแล้ว (คงเหลือ: {$this->product->quantity})", // แก้จาก stock เป็น quantity
        ];
    }
}
