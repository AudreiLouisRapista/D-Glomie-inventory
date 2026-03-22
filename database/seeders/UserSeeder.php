<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      DB::table('users')->insert([
        [
            'role_id' => 1,
            'usr_name' => "Lome 1",
            "email" => "lome1@gmail.com",
            "password" => Hash::make('admin1'),
            "created_at" => now(),
            "updated_at" => now(),
        ],
      ]);
    }
}
