<?php

namespace Modules\CompanyProperty\Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Modules\Company\App\Models\Company;
use Modules\CompanyProperty\App\Models\Detail;
use Modules\CompanyProperty\App\Models\Category;
use Modules\CompanyProperty\App\Models\PropertyType;
use Modules\CompanyProperty\App\Models\CompanyProperty;
use Modules\CompanyProperty\App\Models\CategoryTargetType;

class CompanyPropertyDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        // $statuses = ['Active', 'Under Construction', 'Under Development', 'Under Maintenance'];

        for ($i = 0; $i < 30; $i++) {

            $category = Category::inRandomOrder()->where('name', '!=', 'Others')->first();
            $target_type = CategoryTargetType::inRandomOrder()->where('category_id', $category->id)->first();

            $property = CompanyProperty::create([
                'company_id' => Company::inRandomOrder()->first()->id,
                'category_id' => $category->id,
                'target_type_id' => $target_type->id,
                'type_id' => PropertyType::inRandomOrder()->first()->id,
                'source_property_id' => null,
                'name' => $faker->name,
                'logo' => $faker->name,
                'poster' => $faker->name,
                'currency' => $faker->name,
                'value' => 1000,
                'country' => $faker->name,
                'state' => $faker->name,
                'city' => $faker->name,
                'area_sector_desctrict' => $faker->name,
                'latitude' => $faker->name,
                'longitude' => $faker->name,
                'description' => $faker->realText(50),
                'full_video' => $faker->name,

                // 'property_sub_type_id'  => null,
                // 'property_type_id'  => PropertyType::inRandomOrder()->first()->id,
                // 'user_id'   => User::inRandomOrder()->first()->id,
                // 'name'  => $name,
                // 'addmail' => $faker->asciify('********'),
                // 'status'    => $statuses[array_rand($statuses)],
                // 'description'   => $faker->realText(50),
                // 'slug' => Str::slug($name . ' ' . $i, '-')
            ]);

            for ($a = 1; $a <= 3; $a++) {
                $property->details()->attach(Detail::inRandomOrder()->first()->id, [
                    'value' => $faker->name
                ]);
            }
        }
    }
}
