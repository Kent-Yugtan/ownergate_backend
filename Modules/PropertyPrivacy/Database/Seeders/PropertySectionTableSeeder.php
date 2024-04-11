<?php

namespace Modules\PropertyPrivacy\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\CompanyPrivacy\App\Models\Section;

class PropertySectionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            [
                'name' => 'Property Source',
                'module_name' => 'Property'
            ],
            [
                'name' => 'Property Description',
                'module_name' => 'Property'
            ],
            [
                'name' => 'Property Overview',
                'module_name' => 'Property'
            ],
            [
                'name' => 'Property Details',
                'module_name' => 'Property'
            ],
            [
                'name' => 'Features',
                'module_name' => 'Property'
            ],
            [
                'name' => 'Property Amenities',
                'module_name' => 'Property'
            ],
            [
                'name' => 'Property Utilities',
                'module_name' => 'Property'
            ],
            [
                'name' => 'Property Owner Details',
                'module_name' => 'Property'
            ],
            [
                'name' => 'Property Unitality Details',
                'module_name' => 'Property'
            ],
            [
                'name' => 'Property Condition Roles Details',
                'module_name' => 'Property'
            ],
            [
                'name' => 'Property Additional Remark',
                'module_name' => 'Property'
            ],
            [
                'name' => 'Property Whats Nearby',
                'module_name' => 'Property'
            ],
            [
                'name' => 'Property Address Details',
                'module_name' => 'Property'
            ],
            [
                'name' => 'Property Map Location',
                'module_name' => 'Property'
            ],
            [
                'name' => 'Property View Live',
                'module_name' => 'Property'
            ],
            [
                'name' => 'Property Plans',
                'module_name' => 'Property'
            ],
            [
                'name' => 'Full Video',
                'module_name' => 'Property'
            ],
            [
                'name' => '360 Virtual Tour',
                'module_name' => 'Property'
            ],
            [
                'name' => '360 Virtual Spots',
                'module_name' => 'Property'
            ],
            [
                'name' => 'Property Photos',
                'module_name' => 'Property'
            ],
            
        ];

        foreach ($sections as $section) {
            Section::create($section);
        }
    }
}
