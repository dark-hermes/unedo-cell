<?php

namespace App\Livewire\Partials;

use Livewire\Component;

class Header extends Component
{
    public $unreadCount;
    public $notifications;

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        if (auth()->check()) {
            $this->notifications = auth()->user()->notifications()
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            $this->unreadCount = auth()->user()->unreadNotifications()->count();
        }
    }

    public function markAsRead($notificationId)
    {
        auth()->user()->notifications()->where('id', $notificationId)->update(['read_at' => now()]);
        $this->loadNotifications();
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->loadNotifications();
    }

    public function render()
    {
        $cartCount = auth()->check() ? auth()->user()->carts()->count() : 0;
        $wishlistCount = auth()->check() ? auth()->user()->productWishlists()->count() : 0;

        return view('livewire.partials.header', [
            'cartCount' => $cartCount,
            'wishlistCount' => $wishlistCount,
        ]);
    }
}
