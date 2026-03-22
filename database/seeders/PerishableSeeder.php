<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerishableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('perishable')->insert([
        [
            'id' => 1,
            'perishable_type' => "Perishable",
            "created_at" => now(),
            "updated_at" => now(),
         
        ],
          [
            'id' => 2,
            'perishable_type' => "Non-Perishable",
            "created_at" => now(),
            "updated_at" => now(),
         
        ],
      ]);
    }
}
