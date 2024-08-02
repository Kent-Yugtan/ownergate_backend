<?php

namespace Modules\CompanyProperty\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\companyproperty\App\Models\PropertyAvailableSetting;

class PropertyAvailableSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        PropertyAvailableSetting::truncate();

        $available_columns = [
            'Customer ID', 'Customer Name', 'AddMail', 'Account ID', 'Assigned To', 'Assigned By', 'Incharge By', 'Maintenence',
            'Type', 'Agent', 'Realstate', 'Owner', 'Developer', 'Vendor', 'Property Name', 'Property ID', 'Property Status', 'Reservation',
            'Contracts', 'Approvals', 'Task', 'Reports', 'Ads', 'Channel', 'Active', 'Date', 'Start Date', 'End Date', 'Amount',
            'Period', 'Tel', 'Fax', 'Email', 'Mobile', 'Address', 'City', 'Country', 'Payment Voucher', 'Receipt Voucher', 'Employee'
        ];

        foreach ($available_columns as $v) {
            PropertyAvailableSetting::insert(["name" => $v]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
