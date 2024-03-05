<?php

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Admin',
            'Owner',
            'Developer',
            'Real Estate',
            'Vendor',
            'Agent',
            'Employee',
            'Customer',
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role, 'guard_name' => 'api']);
        }
    }
}
