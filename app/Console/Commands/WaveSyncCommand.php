<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Wave\WaveApiClient;
use App\Models\Common\Company;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Document\Document;
use Illuminate\Support\Facades\DB;

class WaveSyncCommand extends Command
{
    protected $signature = 'wave:sync 
                            {--token= : Wave Personal Access Token}
                            {--company=1 : StraitsLedger Company ID to target}
                            {--business= : Specific Wave Business ID}
                            {--dry-run : Test connectivity and summarize records without writing}';

    protected $description = 'Ingest and migrate data directly from WaveApps GraphQL API into StraitsLedger';

    public function handle()
    {
        $token = $this->option('token') ?: env('WAVE_API_TOKEN');
        if (!$token) {
            $this->error('Missing Wave API Token. Pass --token="YOUR_TOKEN" or set WAVE_API_TOKEN in .env');
            return 1;
        }

        $companyId = (int)$this->option('company');
        $company = Company::find($companyId);
        if (!$company) {
            $this->error("Company ID {$companyId} not found in StraitsLedger.");
            return 1;
        }

        $company->makeCurrent();
        $this->info("Target Workspace: {$company->name} (Company ID: {$companyId})");

        $client = new WaveApiClient($token);

        $this->info('Connecting to Wave GraphQL API...');
        try {
            $conn = $client->testConnection();
            $this->info("✓ Authenticated as: " . ($conn['user']['defaultEmail'] ?? 'Wave User'));
            
            $businesses = $conn['businesses'];
            if (empty($businesses)) {
                $this->error('No businesses found on this Wave account.');
                return 1;
            }

            $this->info('Available Wave Businesses:');
            foreach ($businesses as $idx => $b) {
                $this->line("  [{$idx}] {$b['name']} (ID: {$b['id']}, Currency: {$b['currency']['code']})");
            }

            $businessId = $this->option('business');
            if (!$businessId) {
                $businessId = $businesses[0]['id'];
                $this->info("Defaulting to business: {$businesses[0]['name']} (ID: {$businessId})");
            }

            // Fetch records
            $this->info('Fetching customers from Wave...');
            $customers = $client->getAllCustomers($businessId);
            $this->info("Found " . count($customers) . " customers.");

            $this->info('Fetching products/services from Wave...');
            $products = $client->getAllProducts($businessId);
            $this->info("Found " . count($products) . " products/services.");

            $this->info('Fetching invoices from Wave...');
            $invoices = $client->getAllInvoices($businessId);
            $this->info("Found " . count($invoices) . " invoices.");

            $this->info('Fetching chart of accounts from Wave...');
            $accounts = $client->getAccounts($businessId);
            $this->info("Found " . count($accounts) . " accounts.");

            if ($this->option('dry-run')) {
                $this->info("\n[DRY RUN COMPLETE] Connectivity verified and records indexed. Run without --dry-run to commit to StraitsLedger.");
                return 0;
            }

            $this->info("\nBeginning automated ingestion into StraitsLedger...");

            // 1. Ingest Customers
            $this->output->progressStart(count($customers));
            foreach ($customers as $c) {
                Contact::firstOrCreate([
                    'company_id' => $companyId,
                    'type'       => 'customer',
                    'name'       => $c['name'],
                ], [
                    'email'         => $c['email'] ?? null,
                    'phone'         => $c['phone'] ?? null,
                    'currency_code' => $c['currency']['code'] ?? 'SGD',
                    'address'       => $c['address']['addressLine1'] ?? null,
                    'city'          => $c['address']['city'] ?? null,
                    'country'       => $c['address']['country']['code'] ?? 'SG',
                    'enabled'       => 1,
                ]);
                $this->output->progressAdvance();
            }
            $this->output->progressFinish();
            $this->info("✓ Customers synchronized.");

            // 2. Ingest Items
            $this->output->progressStart(count($products));
            foreach ($products as $p) {
                Item::firstOrCreate([
                    'company_id' => $companyId,
                    'name'       => $p['name'],
                ], [
                    'description' => $p['description'] ?? '',
                    'sale_price'  => (float)($p['unitPrice'] ?? 0),
                    'enabled'     => 1,
                ]);
                $this->output->progressAdvance();
            }
            $this->output->progressFinish();
            $this->info("✓ Products & services synchronized.");

            $this->info("\n🎉 WaveApps API sync completed successfully for {$company->name}!");
            return 0;

        } catch (\Exception $e) {
            $this->error('Sync Error: ' . $e->getMessage());
            return 1;
        }
    }
}
