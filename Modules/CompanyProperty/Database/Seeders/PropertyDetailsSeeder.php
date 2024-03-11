<?php

namespace Modules\CompanyProperty\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\CompanyProperty\App\Models\Detail;

class PropertyDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);

        Detail::insert([
            ['name' => 'Unit Description',],
            ['name' => 'Type',],
            ['name' => 'Floor Number',],
            ['name' => 'Unit Number',],
            ['name' => 'Size Area',],
            ['name' => 'Build Area',],
            ['name' => 'Built Year',],
            ['name' => 'Front Yard',],
            ['name' => 'Back Yard',],
            ['name' => 'Building Type',],
            ['name' => 'Beds',],
            ['name' => 'Rooms',],
            ['name' => 'Status',],
            ['name' => 'Baths',],
            ['name' => 'Permission Number',],
            ['name' => 'Ownership',],
            ['name' => 'Owner Name',],
            ['name' => 'Owner Details',],
        ]);
    }
}
