<?php

namespace Modules\CompanyProperty\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\CompanyProperty\App\Models\Utility;

class UtilitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $utilities = [
            ['name' => 'Electricity'],
            ['name' => 'Water Supply'],
            ['name' => 'Heating'],
            ['name' => 'Internet and Cable TV'],
            ['name' => 'Cooling/Air Conditioning'],
            ['name' => 'Telephone Services'],
            ['name' => 'Natural Gas or Propane'],
            ['name' => 'Sewage/Septic Systems'],
            ['name' => 'Outdoor spa'],
            ['name' => 'Security Systems'],
            ['name' => 'Landscaping and Grounds Maintenance'],
            ['name' => 'Parking Facilities'],
            ['name' => 'Recycling Services'],
            ['name' => 'Stormwater Management'],
            ['name' => 'Emergency Services'],
            ['name' => 'Security Systems'],
            ['name' => 'Mail and Package Delivery'],
            ['name' => 'Emergency Services'],
            ['name' => 'Security Systems'],
            ['name' => 'Tenant Portals or Online Services'],
            ['name' => 'Dry Cleaning or Laundry Services'],

            ['name' => 'Laundry room'],
            ['name' => 'Energy-efficient lighting'],
            ['name' => 'Functioning heating system'],
            ['name' => 'Ventilation for indoor air quality'],
            ['name' => 'Air conditioning for climate control'],
            ['name' => 'Garbage collection services'],
            ['name' => 'Recycling facilities'],
            ['name' => 'Composting options'],
            ['name' => 'Sufficient electrical capacity'],
            ['name' => 'Backup power systems (generators, UPS)'],
            ['name' => 'Adequate water supply for operations'],

            ['name' => 'Adequate water supply for operations'],
            ['name' => 'Proper sewage disposal systems'],
            ['name' => 'Water conservation measures'],
            ['name' => 'Efficient heating and cooling systems'],
            ['name' => 'Climate control for specialized areas'],
            ['name' => 'Regular maintenance of HVAC equipment'],
            ['name' => 'Teleconferencing facilities'],
            ['name' => 'Surveillance cameras'],
            ['name' => 'Access control systems'],
            ['name' => 'Fire detection and suppression'],

            ['name' => 'Elevator Services'],
            ['name' => 'Fire Protection Systems'],
            ['name' => 'Air Quality Systems'],
            ['name' => 'Energy Efficiency Measures'],
            ['name' => 'Community Amenities'],
            ['name' => 'Common Area Maintenance (CAM) Services'],
            ['name' => 'Furniture and Appliance Rentals'],
            ['name' => 'Security Personnel'],
            ['name' => 'Emergency Backup Systems'],
            ['name' => 'Public Transportation Access'],
            ['name' => 'Bike Storage Facilities'],
            ['name' => 'Pet Waste Stations'],
            ['name' => 'Guest Services'],
            ['name' => 'Social Events and Community Programs'],
            ['name' => 'Education and Fitness Programs'],
            ['name' => 'Insurance Services'],
            ['name' => 'Trash Chutes or Valet Trash Services'],
            ['name' => 'Wi-Fi in Common Areas'],
            ['name' => 'Storage Lockers'],
            ['name' => 'Public Transportation Access'],

            ['name' => 'Water supply for industrial processes'],
            ['name' => 'Wastewater treatment facilities'],
            ['name' => 'Availability of water connections'],
            ['name' => 'Access to electrical grid'],
            ['name' => 'Soil suitability for construction'],
            ['name' => 'Flood risk assessment'],
            ['name' => 'Conservation easements or restrictions'],
            ['name' => 'Zoning for specific land uses'],
            ['name' => 'Permits for utility connections'],
            ['name' => 'Cost of utility hookups'],
            ['name' => 'Reliable water supply in rooms'],
        ];


        foreach ($utilities as $utility) {
            $category = Utility::create($utility);
        }

    }
}
