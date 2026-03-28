<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          DB::table('status')->insert([
            [
                'id' => 1,
                'status_type' => 'In Stock',
                'created_at' => now(),
                'updated_at' => now(),

            ],
             [
                'id' => 2,
                'status_type' => 'Low Stock',
                'created_at' => now(),
                'updated_at' => now(),

            ],
             [
                'id' => 3,
                'status_type' => 'Out of Stock',
                'created_at' => now(),
                'updated_at' => now(),

            ],
       ]);
    }
}
