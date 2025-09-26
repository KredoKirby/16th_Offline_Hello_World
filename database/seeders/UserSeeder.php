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
            'email_verified_at' => $now,
            'created_at'        => $now,
            'updated_at'        => $now,
        ]);

        $bulk   = [];
        $roles  = [2, 3, 4];
        $total  = 20;

        for ($i = 0; $i < $total; $i++) {
            $bulk[] = [
                'name'              => "User".$i,
                'email'             => "User".$i."@gmail.com",
                'password'          => Hash::make('password123'),
                'role_id'           => $roles[array_rand($roles)],
                'about'             => $faker->paragraphs(3, true),
                'email_verified_at' => $now,
                'created_at'        => $now,
                'updated_at'        => $now,
            ];

            if (count($bulk) === 500) {
                DB::table('users')->insert($bulk);
                $bulk = [];
            }
        }

        if ($bulk) {
            DB::table('users')->insert($bulk);
        }
    }
}