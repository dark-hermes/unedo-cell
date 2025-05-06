<?php

namespace App\Services;

use Telegram\Bot\Api;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Log;

class TelegramNotificationService
{
    protected $telegram;

    public function __construct()
    {
        $this->telegram = new Api(config('telegram.bots.mybot.token'));
    }

    public function sendToAdmin($message)
    {
        try {
            $this->telegram->sendMessage([
                'chat_id' => env('TELEGRAM_CHAT_ID'),
                'text' => $message
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error("Gagal mengirim ke admin: {$e->getMessage()}");
            return false;
        }
    }

    public function sendOrderNotification($order)
    {
        $message = "Pesanan baru dari {$order->user->name}:\n";
        $message .= "ID Pesanan: {$order->transaction->transaction_code}\n";
        $message .= "Total Harga: {$order->total_price}\n";
        $message .= "Alamat Pengiriman: {$order->address}\n";
        $message .= "Metode Pengiriman: {$order->shipping_method}\n";
        $message .= "Catatan: {$order->note}\n";
        $message .= "URL Pesanan: " . route('admin.orders.show', ['order' => $order->id]) . "\n";

        return $this->sendToAdmin($message);
    }

    public function sendPaymentNotification($order)
    {
        $message = "Pesanan {$order->transaction->transaction_code} telah dibayar.\n\n";
        $message .= "Nama: {$order->user->name}\n";
        $message .= "Alamat: {$order->address}\n\n";
        $message .= "Total Harga: {$order->total_price}\n";
        $message .= "Metode Pembayaran: {$order->transaction->payment_method}\n";
        $message .= "URL Pesanan: " . route('admin.orders.show', ['order' => $order->id]) . "\n";

        return $this->sendToAdmin($message);
    }
}