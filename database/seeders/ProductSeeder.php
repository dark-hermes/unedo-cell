<?php

namespace Database\Seeders;

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

        foreach ($categories as $category) {
            for ($i = 0; $i < 5; $i++) {
                $category->products()->create([
                    "name" => fake()->word(),
                    "description" => fake()->paragraph(rand(2, 5)),
                    "price" => fake()->randomFloat(2, 10000, 100000),
                    "sku" => $category->code . "-" . fake()->unique()->randomNumber(5),
                    "min_stock" => fake()->numberBetween(0, 5),
                ]);
            }
        }
    }
}
