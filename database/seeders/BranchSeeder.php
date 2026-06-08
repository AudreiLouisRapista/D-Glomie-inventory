<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('branches')->insert([
            [
                'branch_name'    => 'Main Branch',
                'branch_address' => 'Main Street, Davao City',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'branch_name'    => 'Branch 2',
                'branch_address' => 'Second Street, Davao City',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}