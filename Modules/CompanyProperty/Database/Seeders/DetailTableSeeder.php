<?php

namespace Modules\CompanyProperty\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\CompanyProperty\App\Models\Detail;
use Modules\CompanyProperty\App\Models\Feature;
use Modules\CompanyProperty\App\Models\Overview;

class DetailTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
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
        ];

        foreach ($types as $type) {
            Detail::create($type);
        }

        $overviews = [
            ['name' => 'Rooms',],
            ['name' => 'Baths',],
            ['name' => 'Bed',],
            ['name' => 'Kitchens',],
            ['name' => 'Year built',],
            ['name' => 'Property type',],
            ['name' => 'Garage',],
            ['name' => 'Build area',],
            ['name' => 'Land Area',],
            ['name' => 'Stores',],
            ['name' => 'Cooling Room',],
            ['name' => 'Mid Room',],
            ['name' => 'Hall',],
            ['name' => 'Lobby',],
            ['name' => 'Balcony',],
            ['name' => 'Security Room',],
            ['name' => 'Floors',],
            ['name' => 'Roof Room',],
            ['name' => 'Roof Area',]
        ];

        foreach ($overviews as $overview) {
            Overview::create($overview);
        }

        $features = [
            ['name' => 'Swimming Pool',],
            ['name' => 'Tennis Court',],
            ['name' => 'Ensuite',],
            ['name' => 'Dishwasher',],
            ['name' => 'Balcony',],
            ['name' => 'Garage',],
            ['name' => 'Study',],
            ['name' => 'Built in robes',],
            ['name' => 'Undercover Parking',],
            ['name' => 'Outdoor area',],
            ['name' => 'Alarm system',],
            ['name' => 'Broadband',],
        ];

        foreach ($features as $feature) {
            Feature::create($feature);
        }
    }
}
