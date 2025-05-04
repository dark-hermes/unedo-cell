<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $categories = ["Case", "Charger", "Headset", "Powerbank", "Screen Protector", "Tempered Glass", "USB Cable"];
        $categories = [
            [
                "name" => "Headset",
                "code" => "HS",
                "image" => "product_categories/headset.png",
            ],
            [
                "name" => "Charger",
                "code" => "CH",
                "image" => "product_categories/charger.png",
            ],
            [
                "name" => "Voucher Smartfren",
                "code" => "S",
                "image" => "products/voucher_smartfren.png",
            ],
            [
                "name" => "Voucher XL",
                "code" => "X",
                "image" => "products/voucher_xl.png",
            ],
            [
                "name" => "Kartu Telkomsel",
                "code" => "TK",
                "image" => "products/kartu_telkomsel.png",
            ],
            [
                "name" => "Voucher Telkomsel",
                "code" => "T",
                "image" => "products/voucher_telkomsel.png",
            ],
            [
                "name" => "Kartu Smartfren",
                "code" => "SK",
                "image" => "products/kartu_smartfren.jpg",
            ],
            [
                "name" => "Kartu XL",
                "code" => "XK",
                "image" => "products/kartu_xl.png",
            ],
            [
                "name" => "Voucher ByU",
                "code" => "B",
                "image" => "products/voucher_byu.png",
            ],
            [
                "name" => "Kartu ByU",
                "code" => "BK",
                "image" => "products/kartu_byu.png",
            ],
            [
                "name" => "Kartu Axis",
                "code" => "AK",
                "image" => "products/kartu_axis.png",
            ],
            [
                "name" => "Voucher Axis",
                "code" => "A",
                "image" => "products/voucher_axis.png",
            ],
        ];

        foreach ($categories as $category) {
            ProductCategory::create([
                "name" => $category["name"],
                "code" => $category["code"],
                "image" => $category["image"] ?? null,
                "description" => fake()->sentence(rand(2, 5)),
            ]);
        }
    }
}
