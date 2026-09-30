<?php

namespace App\Http\Controllers\Api;

use App\Abstracts\Http\Controller;
use Illuminate\Http\Request;
use App\Models\Common\Company;
use App\Models\Document\Document;
use Illuminate\Support\Facades\DB;

class PlatformTelemetryController extends Controller
{
    /**
     * Return executive telemetry metrics for the Straits Master CEO Console.
     */
    public function getTelemetry(Request $request)
    {
        $companies = Company::all();
        $totalCompanies = $companies->count();

        // Calculate aggregated metrics across all companies
        $tenantList = [];
        $totalInvoicesCount = 0;
        $totalRevenueVolume = 0.0;

        foreach ($companies as $comp) {
            $comp->makeCurrent();

            $name = DB::table('settings')->where('company_id', $comp->id)->where('key', 'general.company_name')->value('value') 
                ?? DB::table('settings')->where('company_id', $comp->id)->where('key', 'company.name')->value('value') 
                ?? 'Company #' . $comp->id;

            $taxNumber = DB::table('settings')->where('company_id', $comp->id)->where('key', 'general.company_tax_number')->value('value') 
                ?? DB::table('settings')->where('company_id', $comp->id)->where('key', 'company.tax_number')->value('value') 
                ?? '-';

            $currency = DB::table('settings')->where('company_id', $comp->id)->where('key', 'general.default_currency')->value('value') 
                ?? $comp->currency 
                ?? 'SGD';

            $invoiceCount = Document::invoice()->count();
            $invoicesTotal = (float) Document::invoice()->sum('amount');

            $totalInvoicesCount += $invoiceCount;
            $totalRevenueVolume += $invoicesTotal;

            $tenantList[] = [
                'id'             => $comp->id,
                'name'           => $name,
                'tax_number'     => $taxNumber,
                'currency'       => $currency,
                'invoice_count'  => $invoiceCount,
                'revenue_volume' => $invoicesTotal,
                'status'         => 'active',
                'created_at'     => $comp->created_at ? $comp->created_at->toIso8601String() : null,
            ];
        }

        // Pricing model: S$49/mo per active tenant on StraitsLedger Professional tier
        $mrr = $totalCompanies * 49;
        $arr = $mrr * 12;

        return response()->json([
            'status'     => 'success',
            'timestamp'  => now()->toIso8601String(),
            'platform'   => 'StraitsLedger',
            'metrics'    => [
                'total_companies'     => $totalCompanies,
                'active_companies'    => $totalCompanies,
                'mrr_sgd'             => $mrr,
                'arr_sgd'             => $arr,
                'total_invoices'      => $totalInvoicesCount,
                'total_volume_sgd'    => $totalRevenueVolume,
                'avg_revenue_per_sub' => 49.00,
            ],
            'tenants'    => $tenantList,
        ], 200, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
        ]);
    }
}
