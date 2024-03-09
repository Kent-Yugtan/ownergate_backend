<?php

namespace Modules\Company\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\CompanyProperty\App\Models\Category;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Residential',
            ],
            [
                'name' => 'Commercial',
            ],
            [
                'name' => 'Others',
            ],
        ];

        $targets = [
            [
                'name' => 'Sale',
            ],
            [
                'name' => 'Buy',
            ],
            [
                'name' => 'Rent',
            ],
            [
                'name' => 'Furniture Rented',
            ],
            [
                'name' => 'Rent Sale',
            ],
        ];

        foreach($types as $type) {
            $category = Category::create($type);

            foreach($targets as $target) {
                if ($category->name !== 'Others') {
                    $category->targets()->create($target);
                }
            }
        }
    }
}
