<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Auth\Database\Seeders\RolesTableSeeder;
use Modules\Auth\Database\Seeders\UsersTableSeeder;
use Modules\Company\Database\Seeders\CategoryTableSeeder;
use Modules\Company\Database\Seeders\CompanyDatabaseSeeder;
use Modules\CompanyProperty\Database\Seeders\DetailTableSeeder;
use Modules\CompanyProperty\Database\Seeders\PropertyTypeTableSeeder;
use Modules\CompanyProperty\Database\Seeders\CompanyPropertyDatabaseSeeder;
use Modules\CompanyProperty\Database\Seeders\PropertyOverviewSeeder;
use Modules\CompanyProperty\Database\Seeders\PropertyDetailsSeeder;
use Modules\CompanyProperty\Database\Seeders\PropertyFeaturesSeeder;

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
        $this->call(CompanyDatabaseSeeder::class);
        $this->call(CompanyPropertyDatabaseSeeder::class);
        $this->call(PropertyOverviewSeeder::class);
        $this->call(PropertyDetailsSeeder::class);
        $this->call(PropertyFeaturesSeeder::class);
    }
}
