<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('en_US');
        $now   = now();

        DB::table('users')->insert([
            'name'              => 'Admin User',
            'email'             => 'admin@example.com',
            'password'          => Hash::make('AdminPass123!'),
            'role_id'           => 1, // 1=admin, 2=teacher, 3=student, 4=basic_user
            'about'             => 'System administrator account for testing.',
            'created_at'        => $now,
            'updated_at'        => $now,
        ]);

        $bulk   = [];
        $roles  = [2, 3, 4];
        $total  = 20;

          

        if ($bulk) {
            DB::table('users')->insert($bulk);
        }


        # Student Faker
        
        # Teacher Faker
        
    }
}