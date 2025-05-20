<?php

namespace App\Livewire\Admin\Partials;

use Livewire\Component;

class Topbar extends Component
{
    public $showNotifications = false;
    
    public function toggleNotifications()
    {
        $this->showNotifications = !$this->showNotifications;
        
        // Jika dropdown dibuka, tandai semua notifikasi sebagai dibaca
        if ($this->showNotifications) {
            $this->markAllAsRead();
        }
    }
    
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        $notifications = auth()->user()->notifications()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        $unreadCount = auth()->user()->unreadNotifications()->count();
        
        return view('livewire.admin.partials.topbar', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }
}