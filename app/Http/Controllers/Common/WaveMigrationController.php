<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;
use App\Services\Migration\WaveCSVParser;
use App\Services\Migration\WaveImporter;
use Illuminate\Http\Request;

class WaveMigrationController extends Controller
{
    protected WaveCSVParser $parser;
    protected WaveImporter  $importer;

    public function __construct(WaveCSVParser $parser, WaveImporter $importer)
    {
        $this->parser   = $parser;
        $this->importer = $importer;

        // Use seeded permissions
        $this->middleware('permission:read-sales-customers')->only('index', 'downloadSample');
        $this->middleware('permission:create-sales-customers')->only('upload', 'preview', 'confirm');
    }

    /**
     * Step 1 — Migration Wizard landing / upload page.
     */
    public function index()
    {
        $types = [
            'customers' => [
                'label'       => 'Customers',
                'description' => 'Import your Wave customer list into StraitsLedger contacts.',
                'icon'        => 'groups',
                'sample'      => route('wave-migration.sample', 'customers'),
            ],
            'vendors' => [
                'label'       => 'Vendors / Suppliers',
                'description' => 'Import Wave vendors as StraitsLedger supplier contacts.',
                'icon'        => 'local_shipping',
                'sample'      => route('wave-migration.sample', 'vendors'),
            ],
            'chart_of_accounts' => [
                'label'       => 'Chart of Accounts',
                'description' => 'Import Wave account categories (income & expense types).',
                'icon'        => 'account_tree',
                'sample'      => route('wave-migration.sample', 'chart_of_accounts'),
            ],
            'transactions' => [
                'label'       => 'Transactions',
                'description' => 'Import Wave income and expense transaction history.',
                'icon'        => 'receipt_long',
                'sample'      => route('wave-migration.sample', 'transactions'),
            ],
            'invoices' => [
                'label'       => 'Invoices',
                'description' => 'Import Wave invoice records as StraitsLedger income entries.',
                'icon'        => 'description',
                'sample'      => route('wave-migration.sample', 'invoices'),
            ],
        ];

        return view('common.wave_migration.index', compact('types'));
    }

    /**
     * Step 2 — Parse uploaded CSV and show preview before import.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $result = $this->parser->parse($request->file('csv_file'));

        if ($result['error']) {
            return back()->with('error', $result['error'])->withInput();
        }

        // Store mapped data in session for the confirm step
        session([
            'wave_migration_data'    => $result['mapped'],
            'wave_migration_type'    => $result['type'],
            'wave_migration_preview' => array_slice($result['mapped'], 0, 5),
            'wave_migration_count'   => $result['count'],
            'wave_migration_warnings'=> $result['warnings'],
        ]);

        return view('common.wave_migration.preview', [
            'type'     => $result['type'],
            'typeLabel'=> WaveCSVParser::typeLabel($result['type']),
            'preview'  => array_slice($result['mapped'], 0, 5),
            'count'    => $result['count'],
            'warnings' => $result['warnings'],
            'headers'  => $result['headers'],
        ]);
    }

    /**
     * Step 3 — Execute the actual import from session data.
     */
    public function confirm(Request $request)
    {
        $mapped   = session('wave_migration_data', []);
        $type     = session('wave_migration_type', '');

        if (empty($mapped) || empty($type)) {
            return redirect()->route('wave-migration.index')
                ->with('error', 'No migration data found. Please upload your CSV file again.');
        }

        $result = $this->importer->import($type, $mapped);

        // Clear session
        session()->forget([
            'wave_migration_data',
            'wave_migration_type',
            'wave_migration_preview',
            'wave_migration_count',
            'wave_migration_warnings',
        ]);

        return view('common.wave_migration.result', [
            'type'      => $type,
            'typeLabel' => WaveCSVParser::typeLabel($type),
            'imported'  => $result['imported'],
            'skipped'   => $result['skipped'],
            'errors'    => $result['errors'],
            'summary'   => $result['summary'],
        ]);
    }

    /**
     * Download a sample WaveApps CSV for any data type.
     */
    public function downloadSample(string $type)
    {
        $samplePath = public_path("samples/wave/wave_{$type}_sample.csv");

        if (!file_exists($samplePath)) {
            abort(404, "Sample file for '{$type}' not found.");
        }

        return response()->download(
            $samplePath,
            "wave_{$type}_sample.csv",
            ['Content-Type' => 'text/csv']
        );
    }
}
