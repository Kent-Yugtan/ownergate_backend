<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\CompanyProperty\App\Models\CompanyProperty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UpdatePropertiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $properties = CompanyProperty::with('company')->get();

        foreach ($properties as $property) {
            $company = $property->company;
            $company->updateOgCode($property);
        }
    }
}
