<?php

namespace Database\Seeds;

use Illuminate\Database\Seeder;
use App\Models\Common\Company;
use App\Models\Auth\User;
use App\Models\Setting\Currency;
use App\Models\Setting\Tax;
use Illuminate\Support\Facades\Hash;
use DB;

class StraitsLedgerSeeder extends Seeder
{
    public function run()
    {
        // 1. Create StraitsLedger Company
        $company = Company::firstOrCreate(
            ['domain' => 'localhost'],
            [
                'name' => 'StraitsLedger Pte. Ltd.',
                'email' => 'admin@straitsledger.sg',
                'currency' => 'SGD',
                'domain' => 'localhost',
                'enabled' => 1,
            ]
        );

        // 2. Create SGD Currency
        Currency::firstOrCreate(
            ['code' => 'SGD'],
            [
                'company_id' => $company->id,
                'name' => 'Singapore Dollar',
                'code' => 'SGD',
                'rate' => 1.00000,
                'precision' => 2,
                'symbol' => 'S$',
                'symbol_first' => 1,
                'decimal_mark' => '.',
                'thousands_separator' => ',',
                'enabled' => 1,
            ]
        );

        // 3. Create Singapore 9% GST Tax
        Tax::firstOrCreate(
            ['name' => 'Singapore GST (9%)'],
            [
                'company_id' => $company->id,
                'name' => 'Singapore GST (9%)',
                'rate' => 9.0000,
                'type' => 'normal',
                'enabled' => 1,
            ]
        );

        // 4. Create Admin User
        $user = User::firstOrCreate(
            ['email' => 'admin@straitsledger.sg'],
            [
                'name' => 'StraitsLedger Admin',
                'email' => 'admin@straitsledger.sg',
                'password' => Hash::make('StraitsLedger2026!'),
                'locale' => 'en-GB',
                'enabled' => 1,
            ]
        );

        // Link User to Company
        DB::table('user_companies')->updateOrInsert(
            ['user_id' => $user->id, 'company_id' => $company->id],
            ['user_id' => $user->id, 'company_id' => $company->id]
        );

        echo "StraitsLedger Company & Admin Seeded Successfully!\n";
    }
}
