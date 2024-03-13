<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        for ($i = 0; $i < 10; $i++) {
            DB::table('orders')->insert([
                'user_id' => rand(1, 10),
                'order_date' => date('Y-m-d'),
                'shipping_address' => '123 Test St'
            ]);
        }
    }
}
