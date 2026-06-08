<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('role')->insert([
            ['role' => 'SuperAdmin', 'created_at' => now(), 'updated_at' => now()],
            ['role' => 'Admin',      'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}