<?php

namespace Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StraitsLedgerInit extends Seeder
{
    public function run()
    {
        // 1. Create Company
        $company_id = DB::table('companies')->insertGetId([
            'domain' => 'localhost',
            'enabled' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Company Settings
        $settings = [
            ['company_id' => $company_id, 'key' => 'general.company_name', 'value' => 'StraitsLedger Pte. Ltd.'],
            ['company_id' => $company_id, 'key' => 'general.company_email', 'value' => 'admin@straitsledger.sg'],
            ['company_id' => $company_id, 'key' => 'general.default_currency', 'value' => 'SGD'],
            ['company_id' => $company_id, 'key' => 'general.company_address', 'value' => '71 Ayer Rajah Crescent, Singapore 139951'],
            ['company_id' => $company_id, 'key' => 'wizard.completed', 'value' => '1'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['company_id' => $setting['company_id'], 'key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }

        // 3. Create Admin User
        $user_id = DB::table('users')->insertGetId([
            'name' => 'StraitsLedger Admin',
            'email' => 'admin@straitsledger.sg',
            'password' => Hash::make('StraitsLedger2026!'),
            'locale' => 'en-GB',
            'enabled' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Link User to Company
        DB::table('user_companies')->updateOrInsert(
            ['user_id' => $user_id, 'company_id' => $company_id],
            ['user_id' => $user_id, 'company_id' => $company_id]
        );

        // 4. Create SGD Currency
        DB::table('currencies')->updateOrInsert(
            ['company_id' => $company_id, 'code' => 'SGD'],
            [
                'name' => 'Singapore Dollar',
                'code' => 'SGD',
                'rate' => 1.0000,
                'precision' => 2,
                'symbol' => 'S$',
                'symbol_first' => 1,
                'decimal_mark' => '.',
                'thousands_separator' => ',',
                'enabled' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 5. Create Singapore 9% GST Tax
        DB::table('taxes')->updateOrInsert(
            ['company_id' => $company_id, 'name' => 'Singapore GST (9%)'],
            [
                'rate' => 9.0000,
                'type' => 'normal',
                'enabled' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        echo "StraitsLedger Initialized Cleanly!\n";
    }
}
