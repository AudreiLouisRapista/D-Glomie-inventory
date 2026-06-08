<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'role_id'    => 1,           // SuperAdmin
                'usr_name'   => 'superadmin',
                'email'      => 'superadmin@dglomie.com',
                'password'   => Hash::make('superadmin123'),
                'branch_id'  => 1,           // Main Branch
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id'    => 2,           // Admin
                'usr_name'   => 'admin',
                'email'      => 'admin@dglomie.com',
                'password'   => Hash::make('admin123'),
                'branch_id'  => 1,           // Main Branch
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id'    => 2,           // Admin
                'usr_name'   => 'admin_branch2',
                'email'      => 'admin2@dglomie.com',
                'password'   => Hash::make('admin123'),
                'branch_id'  => 2,           // Branch 2
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id'    => 1,           // Developer
                'usr_name'   => 'developer',
                'email'      => 'developer@dglomie.com',
                'password'   => Hash::make('developer123'),
                'branch_id'  => 1,           // Main Branch
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}