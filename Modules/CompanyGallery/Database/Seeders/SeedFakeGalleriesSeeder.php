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
                'property_id' => 'CP123',  // Should match an existing `og_code` in `company_properties` table
                'assigned_to' => 'OG123',  // Should match an existing `og_code` in `users` table
                'maintained_by' => 'OG456', // Should match an existing `og_code` in `users` table
                'company_id' => 1, // Should match an existing `id` in `companies` table
            ],
            [
                'property_id' => 'CP456',
                'assigned_to' => 'OG789',
                'maintained_by' => 'OG012',
                'company_id' => 1,
            ],
            // Add more entries as needed
        ]);
    }
}
