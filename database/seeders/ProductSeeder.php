<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ProductCategory::all();

        // foreach ($categories as $category) {
        //     for ($i = 0; $i < 5; $i++) {
        //         $sale_price = fake()->randomFloat(2, 10000, 100000);
        //         $category->products()->create([
        //             "sku" => $category->code . "-" . fake()->unique()->randomNumber(5),
        //             "name" => fake()->word(),
        //             "description" => fake()->paragraph(rand(2, 5)),
        //             "sale_price" => $sale_price,
        //             "buy_price" => $sale_price - fake()->randomFloat(2, 1000, 5000),
        //             "min_stock" => fake()->numberBetween(0, 5),
        //         ]);
        //     }
        // }

        $products = [
            [
                "sku" => "T-23",
                "name" => "Voucher Telkomsel 2GB 3 Hari",
                "category_id" => ProductCategory::where("code", "TK")->first()->id,
                "sale_price" => 12000,
                "buy_price" => 10800,
                "min_stock" => 5,
                "description" => fake()->paragraph(rand(1, 3)),
            ],
            [
                "sku" => "T-35",
                "name" => "Voucher Telkomsel 3GB 5 Hari",
                "category_id" => ProductCategory::where("code", "TK")->first()->id,
                "sale_price" => 14000,
                "buy_price" => 12800,
                "min_stock" => 5,
                "description" => fake()->paragraph(rand(1, 3)),
            ],
            [
                "sku" => "T-55",
                "name" => "Voucher Telkomsel 5GB 5 Hari",
                "category_id" => ProductCategory::where("code", "TK")->first()->id,
                "sale_price" => 24000,
                "buy_price" => 21600,
                "min_stock" => 5,
                "description" => fake()->paragraph(rand(1, 3)),
            ],
            [
                "sku" => "T-77",
                "name" => "Voucher Telkomsel 7GB 7 Hari",
                "category_id" => ProductCategory::where("code", "TK")->first()->id,
                "sale_price" => 28000,
                "buy_price" => 26000,
                "min_stock" => 5,
                "description" => fake()->paragraph(rand(1, 3)),
            ],
            [
                "sku" => "T-107",
                "name" => "Voucher Telkomsel 10GB 7 Hari",
                "category_id" => ProductCategory::where("code", "TK")->first()->id,
                "sale_price" => 37000,
                "buy_price" => 34200,
                "min_stock" => 5,
                "description" => fake()->paragraph(rand(1, 3)),
            ],
            [
                "sku" => "HS-VD8572",
                "name" => "Headset Original Vivo",
                "category_id" => ProductCategory::where("code", "HS")->first()->id,
                "sale_price" => 25000,
                "buy_price" => 11500,
                "min_stock" => 1,
                "description" => fake()->paragraph(rand(1, 3)),
                'show' => true,
                'stock_entry' => rand(10,100),
                'image' => 'products/orivivo.jpg',
            ],
            [
                "sku" => "HS-U19112",
                "name" => "Headset Macaron U19",
                "category_id" => ProductCategory::where("code", "HS")->first()->id,
                "sale_price" => 20000,
                "buy_price" => 10000,
                "min_stock" => 1,
                "description" => fake()->paragraph(rand(1, 3)),
                'show' => true,
                'stock_entry' => rand(10,100),
                'image' => 'products/macaron.jpg',
            ],
            [
                "sku" => "HS-PIO-01",
                "name" => "Headset Pioneer Pio 01",
                "category_id" => ProductCategory::where("code", "HS")->first()->id,
                "sale_price" => 45000,
                "buy_price" => 22000,
                "min_stock" => 1,
                "description" => fake()->paragraph(rand(1, 3)),
                'show' => true,
                'stock_entry' => rand(10,100),
                'image' => 'products/pio10.jpeg',
            ],
            [
                "sku" => "CH-S9866",
                "name" => "Charger Samsung S10+ Micro",
                "category_id" => ProductCategory::where("code", "CH")->first()->id,
                "sale_price" => 60000,
                "buy_price" => 35000,
                "min_stock" => 1,
                "description" => fake()->paragraph(rand(1, 3)),
                'show' => true,
                'stock_entry' => rand(10,100),
                'image' => 'products/chs10s.jpeg',
            ],
            [
                "sku" => "CH-SV 9072",
                "name" => "Charger Vooc Samsung Micro",
                "category_id" => ProductCategory::where("code", "CH")->first()->id,
                "sale_price" => 60000,
                "buy_price" => 30000,
                "min_stock" => 1,
                "description" => fake()->paragraph(rand(1, 3)),
                'show' => true,
                'stock_entry' => rand(10,100),
                'image' => 'products/vooc.jpg',
            ],
            [
                "sku" => "CH-SV9854",
                "name" => "Charger Vooc Samsung 4A Micro",
                "category_id" => ProductCategory::where("code", "CH")->first()->id,
                "sale_price" => 40000,
                "buy_price" => 18000,
                "min_stock" => 1,
                "description" => fake()->paragraph(rand(1, 3)),
                'show' => true,
                'image' => 'products/vooc4a.jpeg',
            ],
        ];

        foreach ($products as $product) {
            $createdProduct = Product::create([
                "sku" => $product["sku"],
                "name" => $product["name"],
                "description" => $product["description"],
                "category_id" => $product["category_id"],
                "sale_price" => $product["sale_price"],
                "buy_price" => $product["buy_price"],
                "min_stock" => $product["min_stock"],
                "show" => $product["show"] ?? false,
                "image" => $product["image"] ?? null,
            ]);

            if (isset($product['stock_entry'])) {
                $createdProduct->stockEntries()->create([
                    'user_id' => 1,
                    'quantity' => $product['stock_entry'],
                    'source' => 'purchase',
                    'received_at' => now(),
                ]);
            }
        }
    }
}
