<?php

namespace Database\Seeders;

use App\Models\Option;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $options = [
            [
                'key' => 'SITE_NAME',
                'value' => 'Unedo CELL',
            ],
            [
                'key' => 'STORE_ADDRESS',
                'value' => 'Jl. Diponegoro simpang 3 Sitangkola, Kec. Laguboti, Kab. Toba, Sumatera Utara',
            ],
            [
                'key' => 'STORE_COORDINATE',
                'value' => '2.3497151,99.1261255',
            ],
            [
                'key' => 'STORE_PHONE',
                'value' => '081360010813',
            ],
            [
                'key' => 'STORE_EMAIL',
                'value' => 'unedocell@gmail.com'
            ],
            [
                'key' => 'INSTAGRAM_LINK',
                'value' => 'https://www.instagram.com/unedocell'
            ],
            [
                'key' => 'FACEBOOK_LINK',
                'value' => 'https://www.facebook.com/UnedoCell'
            ],
            [
                'key' => 'BANNER_IMAGE',
                'value' => 'options/home.png',
                'type' => 'image'
            ]
        ];

        foreach ($options as $option) {
            Option::updateOrCreate(
                ['key' => $option['key']],
                [
                    'value' => $option['value'],
                    'type' => $option['type'] ?? 'text',
                ]
            );
        }
    }
}
