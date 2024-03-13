<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {
            DB::table('users')->insert([
                'username' => 'testuser' . $i,
                'password' => bcrypt('password'),
                'email' => 'testuser' . $i . '@example.com',
                'verified' => false,
                'address' => '123 Test St',
                'profile_image' => 'default.jpg',
                'state' => true,
            ]);
        }
    }
}
