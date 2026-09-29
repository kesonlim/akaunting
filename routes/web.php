<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

/**
 * 'web' middleware applied to all routes
 *
 * @see \App\Providers\Route::mapWebRoutes
 */

 Livewire::setScriptRoute(function ($handle) {
    $base = request()->getBasePath();

    return Route::get($base . '/vendor/livewire/livewire/dist/livewire.min.js', $handle);
});

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('platform.dashboard');
    }
    return view('landing.index');
});

// StraitsLedger SaaS Master Control Plane (Founder & CEO Console)
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/platform', [App\Http\Controllers\Platform\SuperAdminController::class, 'index'])->name('platform.dashboard');
    Route::get('/superadmin', function () {
        return redirect()->route('platform.dashboard');
    });
    Route::get('/platform/switch/{company}', [App\Http\Controllers\Platform\SuperAdminController::class, 'switchTenant'])->name('platform.switch');
    Route::post('/platform/provision', [App\Http\Controllers\Platform\SuperAdminController::class, 'provision'])->name('platform.provision');
});

