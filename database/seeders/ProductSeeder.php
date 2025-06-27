<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use DB;
class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Product::factory()->count(10)->create();

        // DB::table('products')->truncate();

        Product::insert([
            [
                'name' => 'Standard Laptop',
                'purchase_price' => 750.00,
                'sell_price' => 999.99,
                'opening_stock' => 25,
                'current_stock' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Basic Mouse',
                'purchase_price' => 8.50,
                'sell_price' => 14.99,
                'opening_stock' => 100,
                'current_stock' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
