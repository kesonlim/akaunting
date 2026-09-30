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

            $plan = DB::table('settings')->where('company_id', $comp->id)->where('key', 'subscription.plan')->value('value') ?? ($comp->id == 1 ? 'Complimentary Lifetime' : 'Professional (S$49/mo)');
            $compMrr = (float)(DB::table('settings')->where('company_id', $comp->id)->where('key', 'subscription.mrr')->value('value') ?? ($comp->id == 1 ? 0.0 : 49.0));
            $status = DB::table('settings')->where('company_id', $comp->id)->where('key', 'subscription.status')->value('value') ?? 'active';

            $invoiceCount = Document::invoice()->count();
            $invoicesTotal = (float) Document::invoice()->sum('amount');

            $totalInvoicesCount += $invoiceCount;
            $totalRevenueVolume += $invoicesTotal;
            $totalMrr = ($totalMrr ?? 0.0) + $compMrr;

            $tenantList[] = [
                'id'             => $comp->id,
                'name'           => $name,
                'tax_number'     => $taxNumber,
                'currency'       => $currency,
                'plan'           => $plan,
                'mrr'            => $compMrr,
                'invoice_count'  => $invoiceCount,
                'revenue_volume' => $invoicesTotal,
                'status'         => $status,
                'created_at'     => $comp->created_at ? $comp->created_at->toIso8601String() : null,
            ];
        }

        $arr = ($totalMrr ?? 0.0) * 12;

        return response()->json([
            'status'     => 'success',
            'timestamp'  => now()->toIso8601String(),
            'platform'   => 'StraitsLedger',
            'metrics'    => [
                'total_companies'     => $totalCompanies,
                'active_companies'    => $totalCompanies,
                'mrr_sgd'             => $totalMrr ?? 0.0,
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
