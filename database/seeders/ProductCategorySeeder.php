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
                "name" => "Case",
                "code" => "CSE",
            ],
            [
                "name" => "Charger",
                "code" => "CHR",
            ],
            [
                "name" => "Headset",
                "code" => "HST",
            ],
            [
                "name" => "Powerbank",
                "code" => "PBK",
            ],
            [
                "name" => "Screen Protector",
                "code" => "SPT",
            ],
            [
                "name" => "Tempered Glass",
                "code" => "TGL",
            ],
            [
                "name" => "USB Cable",
                "code" => "USBC",
            ]
        ];

        foreach ($categories as $category) {
            ProductCategory::create([
                "name" => $category["name"],
                "code" => $category["code"],
                "description" => fake()->sentence(rand(2, 5)),
            ]);
        }
    }
}
