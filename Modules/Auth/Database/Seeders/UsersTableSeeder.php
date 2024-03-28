<?php

namespace Modules\Auth\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'role_name' => 'Admin',
                'og_code' => 'SU OGAD MIN',
                'first_name' => 'Administrator',
                'last_name' => 'Administrator',
                'email' => 'admin@test.com',
                'email_verified_at' => now(),
                'password' => '12345678',
            ],
            [
                'role_name' => 'Customer',
                'og_code' => 'CU 000 000 123',
                'first_name' => 'Customer',
                'last_name' => 'Customer',
                'email' => 'customer@test.com',
                'email_verified_at' => now(),
                'password' => '12345678',
            ],
            [
                'role_name' => 'Owner',
                'og_code' => 'OW OGSA MOON',
                'first_name' => 'Owner',
                'last_name' => 'Owner',
                'email' => 'owner@test.com',
                'email_verified_at' => now(),
                'password' => '12345678',
            ]
        ];

        foreach($users as $user) {
            $user_data = User::create([
                'og_code' => $user['og_code'],
                'email' => $user['email'],
                'email_verified_at' => $user['email_verified_at'],
                'password' => $user['password'],
            ]);

            $user_data->assignRole($user['role_name']);

            $user_data->profile()->create([
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
            ]);

            $user_data->assignRole($user['role_name']);
        }
    }
}
