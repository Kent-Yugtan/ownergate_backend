<?php

namespace Modules\MainScreenPrivacy\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\MainScreen\App\Models\MainScreen;
use Modules\CompanyPrivacy\App\Models\Section;
use Modules\MainScreenPrivacy\App\Models\MainScreenPrivacy;

class MainScreenPrivacyDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $main = MainScreen::first();
        $sections = Section::where('module_name', 'Main Screen')->get();

        foreach ($sections as $section) {
            MainScreenPrivacy::updateOrCreate([
                'main_screen_id' => $main->id,
                'section_id' => $section->id,
            ]);
        }
    }
}
