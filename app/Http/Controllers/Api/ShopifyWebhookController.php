<?php

namespace App\Http\Controllers\Api;

use App\Abstracts\Http\Controller;
use Illuminate\Http\Request;
use App\Models\Common\Company;
use App\Services\Shopify\ShopifySyncService;
use Illuminate\Support\Facades\Log;

class ShopifyWebhookController extends Controller
{
    /**
     * Handle incoming Shopify order/paid webhook.
     */
    public function handle(Request $request, $company_id)
    {
        $company = Company::find($company_id);
        if (!$company) {
            return response()->json(['error' => 'Tenant company not found'], 404);
        }

        $payload = $request->all();
        if (empty($payload)) {
            return response()->json(['error' => 'Empty webhook payload'], 400);
        }

        try {
            $syncService = new ShopifySyncService($company);
            $result = $syncService->syncOrder($payload);

            return response()->json([
                'status'  => 'success',
                'message' => 'Shopify order synchronized successfully with StraitsLedger',
                'data'    => $result,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Shopify Webhook Error: ' . $e->getMessage(), ['company_id' => $company_id]);
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
