<?php

namespace Modules\CompanyProperty\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\CompanyProperty\App\Models\Amenity;
use Modules\CompanyProperty\App\Models\AmenityType;

class AmenitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amenity_types = [
            [
                'name' => 'Unit Amenities',
                'data' => [
                    ['name' => 'Dishwasher'],
                    ['name' => 'In-unit laundry machines'],
                    ['name' => 'Furnished apartment'],
                    ['name' => 'Large windows'],
                    ['name' => 'Valet trash'],
                    ['name' => 'Microwave'],
                    ['name' => 'Hardwood Floors'],
                    ['name' => 'High-end appliances or fixtures'],
                    ['name' => 'Energy-efficient appliances'],
                    ['name' => 'Security systems and intercom'],
                    ['name' => 'Private balcony'],
                    ['name' => 'Pet-friendly'],
                    ['name' => 'Granite countertops'],
                    ['name' => 'Windows covering'],
                    ['name' => 'Walk-In closet'],
                    ['name' => 'Deck or patio'],
                    ['name' => 'Fireplace'],
                    ['name' => 'Large bathtubs'],
                    ['name' => 'Stove'],
                    ['name' => 'Oven'],
                    ['name' => 'Large bathtubs'],
                ]
            ],
            [
                'name' => 'Building Amenities',
                'data' => [
                    ['name' => '24-hour emergency maintenance'],
                    ['name' => 'Resident lounge'],
                    ['name' => 'Common area WiFi'],
                    ['name' => 'Worl/Study area'],
                    ['name' => 'On-site dry cleaners'],
                    ['name' => 'Complimentary coffee bar'],
                    ['name' => 'On-site grocery store'],
                    ['name' => 'Recycling program'],
                    ['name' => 'Library'],
                    ['name' => 'Smart lighting'],
                    ['name' => 'Smart thermostats'],
                    ['name' => 'Online bill pay and maintenance requests'],
                    ['name' => 'Elevator'],
                    ['name' => 'Satellite TV Services'],
                    ['name' => 'CableTV Services'],
                    ['name' => 'Internet Services'],
                    ['name' => 'Telephone Services'],
                ]
            ],
            [
                'name' => 'Parking Amenities',
                'data' => [
                    ['name' => 'Covered parking'],
                    ['name' => 'Garage'],
                    ['name' => 'Designated parking space'],
                    ['name' => 'Bike parking or storage'],
                    ['name' => 'Electric vehicle charging stations'],
                    ['name' => 'Bike repairs shop'],
                    ['name' => 'Dog park'],
                ]
            ],
            [
                'name' => 'Recreational Amenities',
                'data' => [
                    ['name' => 'Grilling Stations'],
                    ['name' => 'Dog park'],
                    ['name' => 'Outdoor Kitchen'],
                    ['name' => 'Picnic area'],
                    ['name' => 'Community garden'],
                    ['name' => 'Bike share program'],
                    ['name' => 'Fitness Center'],
                    ['name' => 'Bike parking'],
                    ['name' => 'Media Room'],
                    ['name' => 'Billiards Table'],
                    ['name' => 'Sport courts'],
                    ['name' => 'SPA'],
                    ['name' => 'Swimming pool'],
                    ['name' => 'Package lockers'],
                    ['name' => 'Yoga Room'],
                    ['name' => 'Party room'],
                    ['name' => 'Sauna Room'],
                    ['name' => 'Rooftop garden'],
                    ['name' => 'Tennis Court'],
                    ['name' => 'Basketball Court'],
                ]
            ],
            [
                'name' => 'Security Amenities',
                'data' => [
                    ['name' => 'On-site Management'],
                    ['name' => 'Secure parking'],
                    ['name' => 'Concierge Services'],
                    ['name' => 'Alarm system'],
                    ['name' => 'Controlled access'],
                    ['name' => 'Motion sensor lights'],
                    ['name' => 'Surveillance cameras'],
                ]
            ],
            [
                'name' => 'Neighborhood Amenities',
                'data' => [
                    ['name' => 'Convenience to major roadways'],
                    ['name' => 'Restaurants'],
                    ['name' => 'Accessible to public transportation'],
                    ['name' => 'Trails and bike paths'],
                    ['name' => 'Nearby supermarket'],
                    ['name' => 'Nearby to public library'],
                    ['name' => 'Coffee shops'],
                    ['name' => 'Nearby worship places'],
                ]
            ],
        ];

        foreach ($amenity_types as $amenity_type) {
            $amenity_db = AmenityType::create([
                'name' => $amenity_type['name']
            ]);

            $amenities = $amenity_type['data'];

            foreach ($amenities as $amenity) {
                $amenity['amenity_type_id'] = $amenity_db->id;
                Amenity::create($amenity);
            }
        }
    }
}
