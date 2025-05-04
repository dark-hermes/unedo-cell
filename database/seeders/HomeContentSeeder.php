<?php

namespace Database\Seeders;

use App\Models\HomeContent;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class HomeContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contents = [
            [
                'title' => 'Reparasi',
                'description' => 'Unedo Cell menyediakan layanan reparasi perangkat elektronik seperti smartphone, tablet, dan laptop dengan penanganan yang teliti dan profesional. Kami menangani berbagai kerusakan, mulai dari layar pecah, baterai melemah, perangkat tidak menyala, hingga masalah konektivitas dan kerusakan akibat air.

Seluruh proses perbaikan dilakukan oleh teknisi berpengalaman menggunakan suku cadang berkualitas untuk menjaga performa dan daya tahan perangkat Anda. Kami mengutamakan ketepatan dalam proses perbaikan untuk memastikan perangkat kembali berfungsi dengan baik.

Layanan kami tersedia untuk berbagai merek dan model perangkat, dengan proses perbaikan yang dilakukan sesuai standar dan prosedur teknis yang tepat.',
                'image' => 'home_contents/reparasi.jpg',
            ],
            [
                'title' => 'Pemesanan',
                'description' => 'Belanja produk elektronik kini lebih mudah bersama Unedo Cell. Kami menyediakan berbagai pilihan gadget, smartphone, aksesoris, dan perangkat elektronik lainnya yang dapat dipesan secara online dengan cepat dan aman. Pelanggan dapat langsung memilih produk melalui katalog di website kami, menambahkannya ke keranjang, dan melanjutkan proses checkout. Kami menyediakan pengiriman dengan jasa ekspedisi terpercaya. Untuk memudahkan pengalaman belanja Anda, kami juga menyediakan informasi detail spesifikasi produk dan fitur pencarian cepat.',
                'image' => 'home_contents/pemesanan.jpg',
            ]
        ];

        foreach ($contents as $content) {
            HomeContent::create([
                'title' => $content['title'],
                'description' => $content['description'],
                'image' => $content['image'],
            ]);
        }
    }
}
