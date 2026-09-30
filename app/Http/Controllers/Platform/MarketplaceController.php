<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\Company;
use App\Services\Wave\WaveApiClient;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use Illuminate\Support\Facades\DB;

class MarketplaceController extends Controller
{
    /**
     * Display the Straits Marketplace & Integrations directory.
     */
    public function index()
    {
        $currentCompany = company();
        $companyId = $currentCompany ? $currentCompany->id : 1;

        // Fetch current integration statuses for this tenant
        $shopifyDomain = setting('shopify.domain', '');
        $shopifyConnected = !empty($shopifyDomain);

        $waveConnected = !empty(setting('wave.api_token', ''));

        return view('platform.marketplace', compact(
            'currentCompany',
            'companyId',
            'shopifyDomain',
            'shopifyConnected',
            'waveConnected'
        ));
    }

    /**
     * Trigger WaveApps direct API cloud sync.
     */
    public function syncWave(Request $request)
    {
        $request->validate([
            'api_token' => 'required|string',
        ]);

        $token = trim($request->input('api_token'));
        $company = company();
        if (!$company) {
            $company = Company::find(1);
            $company->makeCurrent();
        }

        try {
            $client = new WaveApiClient($token);
            $conn = $client->testConnection();

            if (empty($conn['businesses'])) {
                return back()->with('error', 'Connected to Wave, but no businesses were found on this account.');
            }

            $businessId = $conn['businesses'][0]['id'];
            $businessName = $conn['businesses'][0]['name'];

            // 1. Fetch Customers
            $customers = $client->getAllCustomers($businessId);
            $custCount = 0;
            foreach ($customers as $c) {
                Contact::firstOrCreate([
                    'company_id' => $company->id,
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
                $custCount++;
            }

            // 2. Fetch Products
            $products = $client->getAllProducts($businessId);
            $prodCount = 0;
            foreach ($products as $p) {
                Item::firstOrCreate([
                    'company_id' => $company->id,
                    'name'       => $p['name'],
                ], [
                    'description' => $p['description'] ?? '',
                    'sale_price'  => (float)($p['unitPrice'] ?? 0),
                    'enabled'     => 1,
                ]);
                $prodCount++;
            }

            // Save token in settings for ongoing sync
            setting()->set('wave.api_token', $token);
            setting()->set('wave.business_id', $businessId);
            setting()->save();

            return back()->with('success', "Successfully synced with WaveApps ({$businessName})! Imported {$custCount} customers and {$prodCount} products directly into StraitsLedger.");

        } catch (\Exception $e) {
            return back()->with('error', 'Wave Sync Failed: ' . $e->getMessage());
        }
    }

    /**
     * Connect or update Shopify credentials.
     */
    public function connectShopify(Request $request)
    {
        $request->validate([
            'store_domain' => 'required|string',
            'access_token' => 'nullable|string',
        ]);

        $domain = trim($request->input('store_domain'));
        $token = trim($request->input('access_token', ''));

        setting()->set('shopify.domain', $domain);
        if (!empty($token)) {
            setting()->set('shopify.access_token', $token);
        }
        setting()->save();

        return back()->with('success', "Shopify store {$domain} configured! Webhook URL: " . url("/api/webhooks/shopify/" . (company()->id ?? 1)));
    }
}
