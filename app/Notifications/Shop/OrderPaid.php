<?php

namespace App\Notifications\Shop;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrderPaid extends Notification
{
    use Queueable;

    public $order;
    public $itemName;
    public $paymentMethod;
    public $paymentStatus;
    public $paymentDate;
    public $paymentAmount;
    public $userRole;
    public $actionUrl;


    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, string $userRole)
    {
        $this->order = $order;
        $this->itemName = $order->orderItems->first()->name . ' dan ' . ($order->orderItems->count() - 1) . ' barang lainnya';
        $this->paymentMethod = $order->transaction->payment_method;
        $this->paymentStatus = $order->transaction->transaction_status == 'settlement' ? 'Berhasil' : 'Gagal';
        $this->paymentDate = $order->transaction->transaction_time?->format('d-m-Y H:i:s');
        $this->paymentAmount = 'Rp ' . number_format($order->transaction->amount, 0, ',', '.');
        $this->userRole = $userRole;
        $this->actionUrl = $userRole == 'admin' ? url('/admin/orders/' . $order->id) : url('/orders/history');
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
                    ->subject('Pesanan Dibayar')
                    ->lineif($this->userRole == 'admin', 'Pesanan atas barang ' . $this->itemName . ' telah dibayar pada ' . $this->paymentDate)
                    ->lineif($this->userRole == 'user', 'Pesanan Anda atas barang ' . $this->itemName . ' telah dibayar pada ' . $this->paymentDate)
                    ->line('Metode Pembayaran: ' . $this->paymentMethod)
                    ->line('Status Pembayaran: ' . $this->paymentStatus)
                    ->line('Jumlah Pembayaran: ' . $this->paymentAmount)
                    ->lineif($this->userRole == 'admin', 'Silakan proses pesanan pelanggan untuk mengirimkan barang.')
                    ->lineif($this->userRole == 'user', 'Silakan tunggu konfirmasi dari admin untuk memproses pesanan Anda.')
                    ->lineif($this->userRole == 'admin', 'Dipesan oleh: ' . $this->order->user->name)
                    ->lineif($this->userRole == 'admin', 'Nama penerima: ' . $this->order->recipient_name)
                    ->lineif($this->userRole == 'admin', 'Kontak pemesan: ' . $this->order->user->phone)
                    ->lineif($this->userRole == 'admin', 'Kontak penerima: ' . $this->order->recipient_phone)
                    ->lineif($this->userRole == 'admin', 'Alamat pengiriman: ' . $this->order->address)
                    ->lineif($this->userRole == 'admin', 'Catatan: ' . $this->order->note)
                    ->lineif($this->userRole == 'admin', 'Silakan konfirmasi pesanan pelanggan untuk memproses pesanan.')
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
            'title' => 'Pesanan Dibayar',
            'message' => 'Pesanan atas barang ' . $this->itemName . ' telah dibayar pada ' . $this->paymentDate,
            'action_url' => $this->actionUrl,
            'action_text' => 'Lihat Pesanan',
            'user_role' => $this->userRole,
        ];
    }
}
