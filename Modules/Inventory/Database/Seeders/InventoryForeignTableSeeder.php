<?php

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Inventory\App\Models\InventoryType;
use Modules\Inventory\App\Models\InventoryCategoryType;

class InventoryForeignTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Sales'],
            ['name' => 'Premium'],
        ];

        $types = [
            ['item_name' => 'Ads'],
            ['item_name' => 'Plan'],
        ];

        foreach ($categories as $category) {
            InventoryCategoryType::create($category);
        }

        foreach ($types as $type) {
            InventoryType::create($type);
        }
    }
}
