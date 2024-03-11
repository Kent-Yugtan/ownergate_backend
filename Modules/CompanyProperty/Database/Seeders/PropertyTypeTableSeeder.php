<?php

namespace Modules\CompanyProperty\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\CompanyProperty\App\Models\PropertyType;

class PropertyTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Multiple Unit',],
            ['name' => 'Land',],
            ['name' => 'Complex Village',],
            ['name' => 'Tower',],
            ['name' => 'studio',],
            ['name' => 'Cando',],
            ['name' => 'Apartments',],
            ['name' => 'House',],
            ['name' => 'Villa',],
            ['name' => 'Basement',],
            ['name' => 'Residential Buildings',],
            ['name' => 'Village',],
            ['name' => 'Residential Lands',],
            ['name' => 'Office',],
            ['name' => 'Shops',],
            ['name' => 'Commercial Buildings',],
            ['name' => 'Warehouse',],
            ['name' => 'Plaza',],
            ['name' => 'Mall',],
            ['name' => 'Commercial Lands',],
            ['name' => 'Industrial Lands',],
            ['name' => 'Investment',],
            ['name' => 'Commercial',],
            ['name' => 'Rest House',],
            ['name' => 'Rest',],
            ['name' => 'Chalet',],
            ['name' => 'Cottage',],
            ['name' => 'Caravan',],
            ['name' => 'Farm',],
            ['name' => 'Camp',],
            ['name' => 'Vacant Land',],
            ['name' => 'Bed Space',],
            ['name' => 'Room',],
            ['name' => 'Spot',],
            ['name' => 'Location',],
            ['name' => 'Key money',],
            ['name' => 'OTHER',]
        ];

        foreach ($types as $type) {
            $category = PropertyType::create($type);
        }
    }
}
