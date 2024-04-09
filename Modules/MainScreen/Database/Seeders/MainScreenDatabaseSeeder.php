<?php

namespace Modules\MainScreen\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\MainScreen\App\Models\MainScreen;

class MainScreenDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MainScreen::updateOrCreate(
            ['id' => 1], 
            [
                'title' => 'Invest in your property by selling, buying or renting'
            ]
        );
    }
}
