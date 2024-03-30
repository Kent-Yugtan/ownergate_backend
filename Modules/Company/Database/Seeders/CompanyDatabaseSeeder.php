<?php

namespace Modules\Company\Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class CompanyDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $users = User::all();
        $subuser = User::find(3);
        $subuser1 = User::find(2);

        $types = [
            ['company_type_name' => 'Owner', 'company_type_enum' => 'owner', 'description' => $faker->realText(250)],
            ['company_type_name' => 'Developer', 'company_type_enum' => 'developer', 'description' => $faker->realText(250)],
            ['company_type_name' => 'Real Estate', 'company_type_enum' => 'real-estate', 'description' => $faker->realText(250)],
            ['company_type_name' => 'Agent', 'company_type_enum' => 'agent', 'description' => $faker->realText(250)],
            ['company_type_name' => 'Vendor', 'company_type_enum' => 'vendor', 'description' => $faker->realText(250)],
        ];

        foreach ($users as $user) {

            if ($user->hasRole('Customer')) {
                continue;
            }

            $companyName = $user->hasRole('Admin') ? 'Owner Gate' : $faker->name;

            $company = $user->company()->create([
                'company_name' => $companyName,
                'status' => 'active',
                'profile_picture' => $faker->name,
                'profile_poster' => $faker->name,
                'about' => $faker->name,
                'notes' => $faker->name,
                'mission' => $faker->name,
                'vission' => $faker->name,
                'values' => $faker->name,
                'website' => $faker->name,
                'whatsapp_url' => $faker->name,
                'instagram_url' => $faker->name,
                'facebook_url' => $faker->name,
                'twitter_url' => $faker->name,
                'youtube_url' => $faker->name,
                'wechat_url' => $faker->name,
                'telegram_url' => $faker->name,
            ]);

            $company->users()->attach($user->id, ['is_admin' => 1]);
            $company->users()->attach($subuser1->id, ['is_admin' => 0]);

            for ($i = 1; $i <= 3; $i++) {
                $company->managements()->create([
                    'name' => $faker->name,
                    'position' => $faker->name,
                    'image_path' => $faker->name,
                    // 'phone_number' => $faker->phoneNumber,
                ]);
            }

            for ($i = 1; $i <= 3; $i++) {
                $company->news()->create([
                    'title' => $faker->name,
                    'description' => $faker->name,
                    'image_path' => $faker->name,
                    'posted_at' => now()
                ]);
            }

            for ($i = 1; $i <= 4; $i++) {
                $company->services()->create([
                    'title' => $faker->name,
                    'description' => $faker->name,
                    'image_path' => $faker->name,
                ]);
            }

            for ($i = 1; $i <= 4; $i++) {
                $is_default = false;

                if ($i == 2) {
                    $is_default = true;
                }

                $company->locations()->create([
                    'office_name' => $faker->name,
                    'address' => $faker->name,
                    'latitude' => '8.950037',
                    'longitude' => '125.581505',
                    'is_default' => $is_default,
                ]);
            }
        }
    }
}
