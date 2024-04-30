<?php

namespace Modules\MainScreenPrivacy\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\CompanyPrivacy\App\Models\Section;

class MainScreenSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            [
                'name' => 'Banner',
                'module_name' => 'Main Screen'
            ],
            [
                'name' => 'Discover Property by City',
                'module_name' => 'Main Screen'
            ],
            [
                'name' => 'Latest Properties',
                'module_name' => 'Main Screen'
            ],
            [
                'name' => 'Breaking News',
                'module_name' => 'Main Screen'
            ],
            [
                'name' => 'Discover Properties near your location',
                'module_name' => 'Main Screen'
            ],
        ];
        
        foreach ($sections as $section) {
            Section::create($section);
        }
    }
}
