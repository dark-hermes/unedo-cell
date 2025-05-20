<?php

namespace App\Notifications\Shop;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrderCompleted extends Notification
{
    use Queueable;

    public $order;
    public $itemName;
    public $actionUrl;
    public $userRole;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, string $userRole)
    {
        $this->order = $order;
        $this->itemName = $order->orderItems->first()->name . ' dan ' . ($order->orderItems->count() - 1) . ' barang lainnya';
        $this->actionUrl = $userRole == 'admin' ? url('/admin/orders/' . $order->id) : url('/orders/history');
        $this->userRole = $userRole;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Pesanan Selesai')
                    ->lineIf($this->userRole == 'admin', 'Pesanan atas barang ' . $this->itemName . ' telah selesai pada ' . $this->order->updated_at->format('d-m-Y H:i:s'))
                    ->lineIf($this->userRole == 'admin', 'Pesanan telah diterima oleh ' . $this->order->recipient_name)
                    ->lineIf($this->userRole == 'user', 'Pesanan Anda atas barang ' . $this->itemName . ' telah selesai pada ' . $this->order->updated_at->format('d-m-Y H:i:s'))
                    ->lineIf($this->userRole == 'admin', 'Silakan cek status pesanan di halaman admin.')
                    ->lineIf($this->userRole == 'user', 'Silakan cek status pesanan di halaman riwayat pesanan Anda.')
                    ->action('Lihat Pesanan', $this->actionUrl)
                    ->lineIf($this->userRole == 'user', 'Terima kasih telah berbelanja di ' . config('app.name') . '!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'title' => 'Pesanan Selesai',
            'message' => 'Pesanan atas barang ' . $this->itemName . ' telah diterima oleh ' . $this->order->recipient_name,
            'action_url' => $this->actionUrl,
            'user_role' => $this->userRole,
        ];
    }
}
