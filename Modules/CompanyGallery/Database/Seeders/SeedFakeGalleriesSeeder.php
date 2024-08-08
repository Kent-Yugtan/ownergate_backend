<?php

namespace Modules\CompanyGallery\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeedFakeGalleriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);
        DB::table('galleries')->insert([
            [
                'property_id' => '1',
                'assigned_to' => '2', // Corresponds to an og_code in users
                'maintained_by' => '1', // Corresponds to an og_code in users
                'company_id' => 1, // Corresponds to an id in companies
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'property_id' => '1',
                'assigned_to' => '1',
                'maintained_by' => '2',
                'company_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'property_id' => '1',
                'assigned_to' => '1',
                'maintained_by' => '1',
                'company_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'property_id' => '1',
                'assigned_to' => '2',
                'maintained_by' => '1',
                'company_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'property_id' => '2',
                'assigned_to' => '2',
                'maintained_by' => '2',
                'company_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'property_id' => '1',
                'assigned_to' => '2', // Corresponds to an og_code in users
                'maintained_by' => '3', // Corresponds to an og_code in users
                'company_id' => 2, // Corresponds to an id in companies
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'property_id' => '5',
                'assigned_to' => '1',
                'maintained_by' => '2',
                'company_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'property_id' => '1',
                'assigned_to' => '2',
                'maintained_by' => '1',
                'company_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'property_id' => '3',
                'assigned_to' => '1',
                'maintained_by' => '2',
                'company_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'property_id' => '1',
                'assigned_to' => '3',
                'maintained_by' => '2',
                'company_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'property_id' => '1',
                'assigned_to' => '5', // Corresponds to an og_code in users
                'maintained_by' => '2', // Corresponds to an og_code in users
                'company_id' => 3, // Corresponds to an id in companies
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'property_id' => '6',
                'assigned_to' => '1',
                'maintained_by' => '2',
                'company_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'property_id' => '8',
                'assigned_to' => '2',
                'maintained_by' => '3',
                'company_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'property_id' => 'PROP014',
                'assigned_to' => 'SU456',
                'maintained_by' => 'SU123',
                'company_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'property_id' => '2',
                'assigned_to' => '2',
                'maintained_by' => '1',
                'company_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        
        ]);
    }
}
