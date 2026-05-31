<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'company_name'     => 'My Company',
            'company_address'  => '',
            'company_phone'    => '',
            'company_email'    => '',
            'currency'         => 'THB',
            'currency_symbol'  => '฿',
            'timezone'         => 'Asia/Bangkok',
            'date_format'      => 'd/m/Y',
            'items_per_page'   => '15',
            'low_stock_notify' => '1',
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
