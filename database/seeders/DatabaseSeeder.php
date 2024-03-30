<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Auth\Database\Seeders\RolesTableSeeder;
use Modules\Auth\Database\Seeders\UsersTableSeeder;
use Modules\Company\Database\Seeders\CategoryTableSeeder;
use Modules\Company\Database\Seeders\CompanyDatabaseSeeder;
use Modules\CompanyProperty\Database\Seeders\DetailTableSeeder;
use Modules\Inventory\Database\Seeders\InventoryDatabaseSeeder;
use Modules\CompanyProperty\Database\Seeders\AmenitiesTableSeeder;
use Modules\CompanyProperty\Database\Seeders\UtilitiesTableSeeder;
use Modules\Inventory\Database\Seeders\InventoryForeignTableSeeder;
use Modules\CompanyProperty\Database\Seeders\PropertyTypeTableSeeder;
use Modules\CompanyPrivacy\Database\Seeders\CompanySectionTableSeeder;
use Modules\CompanyProperty\Database\Seeders\CompanyPropertyDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call([CategoryTableSeeder::class]);
        $this->call([PropertyTypeTableSeeder::class]);
        $this->call(DetailTableSeeder::class);
        $this->call(AmenitiesTableSeeder::class);
        $this->call(UtilitiesTableSeeder::class);
        $this->call(CompanySectionTableSeeder::class);
        $this->call(CompanyDatabaseSeeder::class);
        $this->call(CompanyPropertyDatabaseSeeder::class);
        $this->call(InventoryForeignTableSeeder::class);
        $this->call(InventoryDatabaseSeeder::class);
    }
}
