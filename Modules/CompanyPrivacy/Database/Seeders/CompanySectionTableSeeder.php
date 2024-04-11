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
            [
                'name' => 'Profile Picture',
                'module_name' => 'Company'
            ],
            [
                'name' => 'Cover Photo',
                'module_name' => 'Company'
            ],
            [
                'name' => 'Account Business Name',
                'module_name' => 'Company'
            ],
            [
                'name' => 'Email',
                'module_name' => 'Company'
            ],
            [
                'name' => 'Phone Number',
                'module_name' => 'Company'
            ],
            [
                'name' => 'Website',
                'module_name' => 'Company'
            ],
            [
                'name' => 'Gender',
                'module_name' => 'Company'
            ],
            [
                'name' => 'Date of Birth',
                'module_name' => 'Company'
            ],
            [
                'name' => 'Nationality',
                'module_name' => 'Company'
            ],
            [
                'name' => 'National ID Number',
                'module_name' => 'Company'
            ],
            [
                'name' => 'License Number',
                'module_name' => 'Company'
            ],
            [
                'name' => 'Expiry Date',
                'module_name' => 'Company'
            ],
            [
                'name' => 'Permissions',
                'module_name' => 'Company'
            ],
            [
                'name' => 'Other',
                'module_name' => 'Company'
            ],
            [
                'name' => 'Country',
                'module_name' => 'Company'
            ],
            [
                'name' => 'Provice/State',
                'module_name' => 'Company'
            ],
            [
                'name' => 'City',
                'module_name' => 'Company'
            ],
            [
                'name' => 'Postal/Zip Code',
                'module_name' => 'Company'
            ],
            [
                'name' => 'Address',
                'module_name' => 'Company'
            ],
            [
                'name' => 'AddMail',
                'module_name' => 'Company'
            ],
            [
                'name' => 'Whatsapp',
                'module_name' => 'Company'
            ],
            [
                'name' => 'Instagram',
                'module_name' => 'Company'
            ],
            [
                'name' => 'Facebook',
                'module_name' => 'Company'
            ],
            [
                'name' => 'X',
                'module_name' => 'Company'
            ],
            [
                'name' => 'YouTube',
                'module_name' => 'Company'
            ],
            [
                'name' => 'WeChat',
                'module_name' => 'Company'
            ],
            [
                'name' => 'Telegram',
                'module_name' => 'Company'
            ],
            [
                'name' => 'About',
                'module_name' => 'Company'
            ],
            [
                'name' => 'Mission & Vission & Values',
                'module_name' => 'Company'
            ],
            [
                'name' => 'Management',
                'module_name' => 'Company'
            ],
            [
                'name' => 'Breaking News',
                'module_name' => 'Company'
            ],
            [
                'name' => 'Services',
                'module_name' => 'Company'
            ],
            [
                'name' => 'Locations',
                'module_name' => 'Company'
            ],
            [
                'name' => 'Licenses & Permissions',
                'module_name' => 'Company'
            ]
            
        ];

        foreach ($sections as $section) {
            Section::create($section);
        }
    }
}
