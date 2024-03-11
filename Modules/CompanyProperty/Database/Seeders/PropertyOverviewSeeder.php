<?php

namespace Modules\CompanyProperty\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\CompanyProperty\App\Models\Overview;

class PropertyOverviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);
        Overview::insert([
            [
                'name' => 'Rooms',
            ],
            [
                'name' => 'Baths',
            ],
            [
                'name' => 'Bed',
            ],
            [
                'name' => 'Kitchens',
            ],
            [
                'name' => 'Year built',
            ],
            [
                'name' => 'Property type',
            ],
            [
                'name' => 'Garage',
            ],
            [
                'name' => 'Build area',
            ],
            [
                'name' => 'Land Area',
            ],
            [
                'name' => 'Stores',
            ],
            [
                'name' => 'Cooling Room',
            ],
            [
                'name' => 'Mid Room',
            ],
            [
                'name' => 'Hall',
            ],
            [
                'name' => 'Lobby',
            ],
            [
                'name' => 'Balcony',
            ],
            [
                'name' => 'Security Room',
            ],
            [
                'name' => 'Floors',
            ],
            [
                'name' => 'Roof Room',
            ],
            [
                'name' => 'Roof Area',
            ]
        ]);
    }
}
