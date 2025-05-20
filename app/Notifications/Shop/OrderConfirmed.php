<?php

namespace App\Notifications\Shop;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrderConfirmed extends Notification
{
    use Queueable;

    public $order;
    public $itemName;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->itemName = $order->orderItems->first()->name . ' dan ' . $order->orderItems->count() - 1 . ' barang lainnya';
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
                    ->subject('Pesanan Dikonfirmasi')
                    ->line('Pesanan Anda telah dikonfirmasi!')
                    ->line('Pesanan atas barang' . ' ' . $this->itemName . ' telah dikonfirmasi.')
                    ->line('Silakan lakukan pembayaran untuk memproses pesanan Anda.')
                    ->line('Total Pembayaran: Rp ' . number_format($this->order->total_price, 0, ',', '.'))
                    ->action('Bayar Sekarang', url('/orders/' . $this->order->id . '/payment'))
                    ->line('Terima kasih telah berbelanja di '. config('app.name') . '!');
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
            'title' => 'Pesanan Dikonfirmasi',
            'message' => 'Pesanan atas barang ' . $this->itemName . ' telah dikonfirmasi.',
            'action_url' => url('/orders/' . $this->order->id . '/payment'),
            'action_text' => 'Bayar Sekarang',
        ];
    }
}
