<?php

namespace App\Notifications\Shop;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreated extends Notification
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
        $this->itemName = $order->orderItems->first()->name . ' dan ' . ($order->orderItems->count() - 1) . ' barang lainnya';
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
                    ->subject('Pesanan Baru Masuk!')
                    ->line('Pesanan baru atas barang ' . $this->itemName . ' telah dibuat pada ' . $this->order->created_at->format('d-m-Y H:i:s'))
                    ->line('Nama penerima: ' . $this->order->recipient_name)
                    ->line('Dipesan oleh: ' . $this->order->user->name)
                    ->line('Kontak pemesan: ' . $this->order->recipient_phone)
                    ->line('Alamat pengiriman: ' . $this->order->address)
                    ->line('Total Pembayaran: Rp ' . number_format($this->order->total_price, 0, ',', '.'))
                    ->line('Metode pengiriman: ' . $this->order->shipping_method)
                    ->line('Catatan: ' . $this->order->note)
                    ->line('Silakan konfirmasi pesanan pelanggan untuk memproses pesanan.')
                    ->action('Lihat Pesanan', url('/admin/orders/' . $this->order->id));
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
            'title' => 'Pesanan Baru Masuk!',
            'message' => 'Pesanan baru atas barang ' . $this->itemName . ' telah dibuat',
            'action_url' => url('/admin/orders/' . $this->order->id),
            'action_text' => 'Lihat Pesanan',
        ];
    }
}
