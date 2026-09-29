<?php

namespace App\Http\Controllers\Platform;

use App\Abstracts\Http\Controller;
use App\Models\Auth\User;
use App\Models\Common\Company;
use App\Models\Document\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $companies = Company::all()->map(function ($company) {
            $name = DB::table('settings')->where('company_id', $company->id)->where('key', 'general.company_name')->value('value') 
                ?? DB::table('settings')->where('company_id', $company->id)->where('key', 'company.name')->value('value') 
                ?? 'Company #' . $company->id;
            
            $uen = DB::table('settings')->where('company_id', $company->id)->where('key', 'company.tax_number')->value('value')
                ?? DB::table('settings')->where('company_id', $company->id)->where('key', 'general.company_tax_number')->value('value')
                ?? 'UEN Pending';

            $userCount = DB::table('user_companies')->where('company_id', $company->id)->count();
            $invoiceCount = Document::withoutGlobalScopes()->where('company_id', $company->id)->where('type', 'invoice')->count();

            // Tier assignment
            $tiers = [
                1 => ['plan' => 'Enterprise Sovereign', 'price' => 299, 'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-300'],
                2 => ['plan' => 'Straits Internal Demo', 'price' => 0, 'badge' => 'bg-slate-100 text-slate-800 border-slate-300'],
                3 => ['plan' => 'Business Pro', 'price' => 49, 'badge' => 'bg-blue-100 text-blue-800 border-blue-300'],
                4 => ['plan' => 'Accounting Practice', 'price' => 89, 'badge' => 'bg-purple-100 text-purple-800 border-purple-300'],
            ];

            $tierInfo = $tiers[$company->id] ?? ['plan' => 'Business Pro', 'price' => 49, 'badge' => 'bg-blue-100 text-blue-800 border-blue-300'];

            return (object) [
                'id' => $company->id,
                'name' => $name,
                'uen' => $uen,
                'enabled' => $company->enabled,
                'created_at' => $company->created_at,
                'user_count' => max(1, $userCount),
                'invoice_count' => $invoiceCount,
                'plan' => $tierInfo['plan'],
                'mrr' => $tierInfo['price'],
                'badge' => $tierInfo['badge'],
            ];
        });

        // Financial Aggregations
        $totalMrr = $companies->sum('mrr');
        $totalArr = $totalMrr * 12;
        $activeTenantsCount = $companies->where('enabled', true)->count();
        $totalUsersCount = User::count();
        $totalInvoicesCount = Document::withoutGlobalScopes()->where('type', 'invoice')->count();

        return view('platform.dashboard', compact(
            'companies',
            'totalMrr',
            'totalArr',
            'activeTenantsCount',
            'totalUsersCount',
            'totalInvoicesCount'
        ));
    }

    public function switchTenant($companyId)
    {
        $company = Company::findOrFail($companyId);
        
        // Link user to this company if not linked
        $user = auth()->user();
        if (!$user->companies->contains($company->id)) {
            $user->companies()->attach($company->id);
        }

        session(['company_id' => $company->id]);
        return redirect('/' . $company->id . '/dashboard');
    }

    public function provision(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:191',
            'company_uen' => 'required|string|max:20',
            'company_email' => 'required|email|max:191',
            'plan_tier' => 'required|string',
        ]);

        $company = Company::create([
            'domain' => 'localhost',
            'enabled' => 1,
            'currency' => 'SGD',
        ]);

        // Save company settings
        DB::table('settings')->insert([
            ['company_id' => $company->id, 'key' => 'general.company_name', 'value' => $request->company_name],
            ['company_id' => $company->id, 'key' => 'company.name', 'value' => $request->company_name],
            ['company_id' => $company->id, 'key' => 'general.company_email', 'value' => $request->company_email],
            ['company_id' => $company->id, 'key' => 'company.email', 'value' => $request->company_email],
            ['company_id' => $company->id, 'key' => 'general.company_tax_number', 'value' => strtoupper($request->company_uen)],
            ['company_id' => $company->id, 'key' => 'company.tax_number', 'value' => strtoupper($request->company_uen)],
            ['company_id' => $company->id, 'key' => 'general.default_currency', 'value' => 'SGD'],
            ['company_id' => $company->id, 'key' => 'wizard.completed', 'value' => '1'],
        ]);

        // Attach current user as admin of this new tenant
        $user = auth()->user();
        $user->companies()->attach($company->id);

        return redirect()->route('platform.dashboard')->with('success', 'Tenant "' . $request->company_name . '" provisioned successfully on ' . $request->plan_tier . ' plan!');
    }
}
