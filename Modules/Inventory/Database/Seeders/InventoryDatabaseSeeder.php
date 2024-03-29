<?php

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Inventory\App\Models\Inventory;

class InventoryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inventories = [
            [
                'admin_id' => 1,
                'inventory_category_id' => 1,
                'inventory_type_id' => 1,
                'item_id' => 'test',
                'description_name' => 'test',
                'level' => 'test',
                'full_description' => 'test',
                'country' => 'test',
                'city' => 'test',
                'area' => 'test',
                'price' => 1000,
                'discount' => '10%',
                'current_date' => now(),
                'account_id' => 'test',
                'opening_balance' => 'test',
                'vendor_id' => 'test',
                'start_date' => now(),
                'end_date' => now()->addDays(3),
                'item_id_details' => 'test',
                'is_active' => 1,
            ],
            [
                'admin_id' => 1,
                'inventory_category_id' => 2,
                'inventory_type_id' => 2,
                'item_id' => 'test',
                'description_name' => 'test',
                'level' => 'test',
                'full_description' => 'test',
                'country' => 'test',
                'city' => 'test',
                'area' => 'test',
                'price' => 1500,
                'discount' => '20%',
                'current_date' => now(),
                'account_id' => 'test',
                'opening_balance' => 'test',
                'vendor_id' => 'test',
                'start_date' => now(),
                'end_date' => now()->addDays(3),
                'item_id_details' => 'test',
                'is_active' => 1,
            ]
        ];
      
        foreach ($inventories as $inventory) {
            Inventory::create($inventory);
        }
    }
}
