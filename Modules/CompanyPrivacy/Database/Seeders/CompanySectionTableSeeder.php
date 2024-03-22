<?php

namespace Modules\CompanyPrivacy\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\CompanyPrivacy\App\Models\Section;

class CompanySectionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            ['name' => 'Profile Picture'],
            ['name' => 'Cover Photo'],
            ['name' => 'Account Business Name'],
            ['name' => 'Email'],
            ['name' => 'Phone Number'],
            ['name' => 'Website'],
            ['name' => 'Gender'],
            ['name' => 'Date of Birth'],
            ['name' => 'Nationality'],
            ['name' => 'National ID Number'],
            ['name' => 'License Number'],
            ['name' => 'Expiry Date'],
            ['name' => 'Permissions'],
            ['name' => 'Other'],
            ['name' => 'Country'],
            ['name' => 'Provice/State'],
            ['name' => 'City'],
            ['name' => 'Postal/Zip Code'],
            ['name' => 'Address'],
            ['name' => 'AddMail'],
            ['name' => 'Whatsapp'],
            ['name' => 'Instagram'],
            ['name' => 'Facebook'],
            ['name' => 'X'],
            ['name' => 'YouTube'],
            ['name' => 'WeChat'],
            ['name' => 'Telegram'],
            ['name' => 'About'],
            ['name' => 'Mission & Vission & Values'],
            ['name' => 'Management'],
            ['name' => 'Breaking News'],
            ['name' => 'Services'],
            ['name' => 'Locations'],
            ['name' => 'Licenses & Permissions']
            
        ];

        foreach ($sections as $section) {
            Section::create($section);
        }
    }
}
