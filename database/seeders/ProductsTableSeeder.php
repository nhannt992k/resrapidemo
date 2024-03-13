<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        for ($i = 0; $i < 10; $i++) {
            DB::table('products')->insert([
                'name' => 'Product' . $i,
                'description' => 'This is a product description.',
                'price' => rand(100, 1000),
                'seller_id' => rand(1, 10),
                'category_id' => rand(1, 10)
            ]);
        }
    }
}
