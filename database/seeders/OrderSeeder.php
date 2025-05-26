<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user yang aktif
        $users = User::where('is_active', true)->get();

        foreach ($users as $user) {
            // Ambil product yang masih ada stok
            $products = Product::withCurrentStock()
                ->get()
                ->filter(fn ($product) => $product->stock > 0)
                ->shuffle()
                ->take(rand(1, 3)); // max 3 product per order

            if ($products->isEmpty()) {
                continue;
            }

            // Ambil alamat user (pakai satu default atau pertama saja)
            $address = $user->addresses()->first();
            if (!$address) {
                continue; // skip user tanpa alamat
            }

            // Buat order
            $order = Order::create([
                'user_id' => $user->id,
                'address' => $address->address,
                'latitude' => $address->latitude,
                'longitude' => $address->longitude,
                'recipient_name' => $address->recipient_name,
                'recipient_phone' => $address->phone,
                'order_status' => 'completed',
                'receipt_number' => strtoupper(Str::random(10)),
                'shipping_method' => 'unedo',
                'shipping_cost' => rand(5000, 15000),
                'note' => fake()->sentence(),
            ]);

            $totalAmount = 0;

            // Tambahkan item ke order
            foreach ($products as $product) {
                $maxQty = min(5, $product->stock);
                $qty = rand(1, $maxQty);
                $unitPrice = $product->price_after_discount;

                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'sku' => $product->sku,
                    'name' => $product->name,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'note' => fake()->sentence(),
                ]);

                $totalAmount += $qty * $unitPrice;

                // Kurangi stok produk
                $orderItem->product->stockOutputs()->create([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'quantity' => $qty,
                    'reason' => 'sale',
                    'note' => $order->recipient_name . ' : ' . $order->recipient_phone,
                    'output_date' => now(),
                ]);
            }

            // Buat transaksi untuk order
            Transaction::create([
                'order_id' => $order->id,
                'transaction_code' => strtoupper('TRX' . Str::random(8)),
                'amount' => $totalAmount + $order->shipping_cost,
                'payment_method' => fake()->randomElement(['gopay', 'bank_transfer', 'qris']),
                'transaction_status' => 'settlement',
                'snap_token' => Str::uuid(),
                'transaction_time' => now(),
                'settlement_time' => now(),
            ]);
        }
    }
}
