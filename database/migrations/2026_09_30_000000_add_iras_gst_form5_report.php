<?php

use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Models\Common\Company;
use App\Models\Common\Report;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Create permission
        try {
            $permission = Permission::firstOrCreate(
                ['name' => 'read-reports-iras-gst-form5'],
                [
                    'display_name' => 'Read IRAS GST Form 5',
                    'description'  => 'Read Inland Revenue Authority of Singapore (IRAS) GST Return Form 5',
                ]
            );

            // Attach to admin and manager roles across all companies
            $roles = Role::whereIn('name', ['admin', 'manager'])->get();
            foreach ($roles as $role) {
                if (!$role->hasPermission('read-reports-iras-gst-form5')) {
                    $role->permissions()->syncWithoutDetaching([$permission->id]);
                }
            }
        } catch (\Throwable $e) {
            // Permission table might vary in test environments
        }

        // 2. Create the report for all existing companies
        try {
            $companies = Company::all();
            foreach ($companies as $company) {
                Report::firstOrCreate(
                    [
                        'company_id' => $company->id,
                        'class'      => 'App\\Reports\\IrasGstForm5',
                    ],
                    [
                        'name'         => 'IRAS GST Form 5',
                        'description'  => 'Official Inland Revenue Authority of Singapore (IRAS) GST Return Form 5 (Boxes 1 to 13)',
                        'settings'     => (object) ['period' => 'quarterly', 'basis' => 'accrual'],
                        'created_from' => 'core::migration',
                    ]
                );
            }
        } catch (\Throwable $e) {
            // Log or ignore if reports table isn't ready
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Report::where('class', 'App\\Reports\\IrasGstForm5')->delete();
        Permission::where('name', 'read-reports-iras-gst-form5')->delete();
    }
};
