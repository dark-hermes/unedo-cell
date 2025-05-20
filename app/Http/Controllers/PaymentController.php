<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Reparation;
use Illuminate\Http\Request;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Auth;
use App\Services\TelegramNotificationService;

class PaymentController extends Controller
{
    public function payment(Order $order)
    {
        // Pastikan order milik user yang login
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Pastikan order belum dibayar
        if ($order->order_status !== 'confirmed' && $order->transaction->transaction_status !== 'pending') {
            return redirect()->route('orders.history')->with('error', 'Pesanan sudah dibayar atau tidak valid.');
        }

        $midtransService = new MidtransService();

        // Siapkan parameter untuk Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $order->id,
                'gross_amount' => $order->total_price,
            ],
            'customer_details' => [
                'first_name' => $order->recipient_name,
                'email' => Auth::user()->email,
                'phone' => $order->recipient_phone,
            ],
            'item_details' => $this->prepareItemDetails($order),
        ];

        // Dapatkan Snap Token
        $snapToken = $midtransService->createSnapToken($params);

        return view('shop.payment', [
            'order' => $order,
            'snapToken' => $snapToken,
        ]);
    }

    private function prepareItemDetails(Order $order): array
    {
        $items = [];

        // Tambahkan produk
        foreach ($order->orderItems as $item) {
            $items[] = [
                'id' => $item->product_id,
                'price' => $item->unit_price,
                'quantity' => $item->quantity,
                'name' => $item->name,
            ];
        }

        // Tambahkan biaya pengiriman sebagai item terpisah
        if ($order->shipping_cost > 0) {
            $items[] = [
                'id' => 'SHIPPING',
                'price' => $order->shipping_cost,
                'quantity' => 1,
                'name' => 'Biaya Pengiriman (' . $this->getShippingMethodName($order->shipping_method) . ')',
            ];
        }

        return $items;
    }

    private function getShippingMethodName(string $method): string
    {
        return match ($method) {
            'self_pickup' => 'Ambil di Toko',
            'unedo' => 'Kurir Unedo',
            'courier' => 'Kurir Ekspedisi',
            default => $method,
        };
    }

    public function paymentSuccess($orderId)
    {
        $order = Order::with('transaction')->findOrFail($orderId);

        // Verifikasi status transaksi
        if ($order->transaction->transaction_status !== 'settlement') {
            $midtransService = new MidtransService();
            $status = $midtransService->checkStatus($order->transaction->transaction_code);

            if ($status->transaction_status === 'settlement') {
                $order->transaction()->update([
                    'transaction_status' => 'settlement',
                    'payment_method' => $status->payment_type,
                    'settlement_time' => now(),
                ]);
                $order->update(['order_status' => 'confirmed']);

                $order = $order->fresh();

                // Notifikasi ke user
                $order->user->notify(new \App\Notifications\Shop\OrderPaid($order, $order->user->getRoleNames()[0]));

                // Notifikasi ke admin
                $telegramService = new TelegramNotificationService();
                $telegramService->sendPaymentNotification($order);

                $admins = User::whereHas('roles', function ($query) {
                    $query->where('name', 'admin');
                })->get();
                foreach ($admins as $admin) {
                    $admin->notify(new \App\Notifications\Shop\OrderPaid($order, $admin->getRoleNames()[0]));
                }
            } else {
                return redirect()->route('orders.payment', ['order' => $orderId])
                    ->with('error', 'Pembayaran belum berhasil, silakan coba lagi.');
            }
        }

        return view('shop.payment.success', compact('order'));
    }

    public function reparationPaymentSuccess($reparationId)
    {
        $reparation = Reparation::with('reparationTransaction')->findOrFail($reparationId);

        if ($reparation->reparationTransaction->transaction_status !== 'settlement') {
            $midtransService = new MidtransService();
            $status = $midtransService->checkStatus($reparation->reparationTransaction->transaction_code);

            if ($status->transaction_status === 'settlement') {
                $reparation->reparationTransaction()->update([
                    'transaction_status' => 'settlement',
                    'payment_method' => $status->payment_type,
                    'settlement_time' => now(),
                ]);

                $telegramService = new TelegramNotificationService();
                $telegramService->sendReparationPaymentNotification($reparation);

                // Notifikasi ke user
                $reparation->user->notify(new \App\Notifications\Reparation\ReparationPaid($reparation, $reparation->user->getRoleNames()[0]));
                // Notifikasi ke admin
                $admins = User::whereHas('roles', function ($query) {
                    $query->where('name', 'admin');
                })->get();
                foreach ($admins as $admin) {
                    $admin->notify(new \App\Notifications\Reparation\ReparationPaid($reparation, $admin->getRoleNames()[0]));
                }
            } else {
                return redirect()->route('reparations.payment', ['reparation' => $reparationId])
                    ->with('error', 'Pembayaran belum berhasil, silakan coba lagi.');
            }
        }
        return view('reparations.payment.success', compact('reparation'));
    }
}
