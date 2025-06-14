<?php
namespace App\Services;

use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createSnapToken(array $params): string
    {
        return Snap::getSnapToken($params);
    }

    public function checkStatus(string $orderId)
    {
        return Transaction::status($orderId);
    }

    public function createTransaction(array $params)
    {
        return Snap::createTransaction($params);
    }

    // public function refund(string $orderId, float $amount, string $reason, ?string $refundKey = null)
    // {
    //     return Transaction::refund($orderId, [
    //         'refund_key' => $refundKey ?? uniqid('refund_'),
    //         'amount' => $amount,
    //         'reason' => $reason,
    //     ]);
    // }
}
