<?php

namespace Modules\CompanyProperty\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\CompanyProperty\App\Models\Feature;

class PropertyFeaturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);

        Feature::insert([
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
        ]);
    }
}
