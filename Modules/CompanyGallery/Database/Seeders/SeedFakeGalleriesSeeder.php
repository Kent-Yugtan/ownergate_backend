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
                'property_id' => 'PROP001',
                'assigned_to' => 'SU123', // Corresponds to an og_code in users
                'maintained_by' => 'SU456', // Corresponds to an og_code in users
                'company_id' => 1, // Corresponds to an id in companies
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'property_id' => 'PROP002',
                'assigned_to' => 'SU456',
                'maintained_by' => 'SU123',
                'company_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'property_id' => 'PROP003',
                'assigned_to' => 'SU123',
                'maintained_by' => 'SU456',
                'company_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'property_id' => 'PROP004',
                'assigned_to' => 'SU456',
                'maintained_by' => 'SU123',
                'company_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'property_id' => 'PROP005',
                'assigned_to' => 'SU123',
                'maintained_by' => 'SU456',
                'company_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'property_id' => 'PROP006',
                'assigned_to' => 'SU456', // Corresponds to an og_code in users
                'maintained_by' => 'SU123', // Corresponds to an og_code in users
                'company_id' => 2, // Corresponds to an id in companies
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'property_id' => 'PROP007',
                'assigned_to' => 'SU123',
                'maintained_by' => 'SU456',
                'company_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'property_id' => 'PROP008',
                'assigned_to' => 'SU456',
                'maintained_by' => 'SU123',
                'company_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'property_id' => 'PROP009',
                'assigned_to' => 'SU123',
                'maintained_by' => 'SU456',
                'company_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'property_id' => 'PROP010',
                'assigned_to' => 'SU456',
                'maintained_by' => 'SU123',
                'company_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'property_id' => 'PROP011',
                'assigned_to' => 'SU123', // Corresponds to an og_code in users
                'maintained_by' => 'SU456', // Corresponds to an og_code in users
                'company_id' => 3, // Corresponds to an id in companies
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'property_id' => 'PROP012',
                'assigned_to' => 'SU456',
                'maintained_by' => 'SU123',
                'company_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'property_id' => 'PROP013',
                'assigned_to' => 'SU123',
                'maintained_by' => 'SU456',
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
                'property_id' => 'PROP015',
                'assigned_to' => 'SU123',
                'maintained_by' => 'SU456',
                'company_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        
        ]);
    }
}
