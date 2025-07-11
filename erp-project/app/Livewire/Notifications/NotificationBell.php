<?php

namespace App\Livewire\Notifications;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationBell extends Component
{
    public $unreadNotifications;
    public $unreadCount;

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $user = Auth::user();
        $this->unreadNotifications = $user->unreadNotifications()->take(10)->get();
        $this->unreadCount = $user->unreadNotifications()->count();
    }

    public function markAsRead($notificationId)
    {
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($notificationId);
        $notification->markAsRead();

        $this->loadNotifications(); // โหลดข้อมูลใหม่
    }

    public function render()
    {
        return view('livewire.notifications.notification-bell');
    }
}