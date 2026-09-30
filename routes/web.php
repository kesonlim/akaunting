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

Route::get('/switch-from-wave', function () {
    return view('landing.switch-from-wave');
})->name('landing.switch-from-wave');

// StraitsLedger SaaS Master Control Plane (Founder & CEO Console)
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/platform', [App\Http\Controllers\Platform\SuperAdminController::class, 'index'])->name('platform.dashboard');
    Route::get('/superadmin', function () {
        return redirect()->route('platform.dashboard');
    });
    Route::get('/platform/switch/{company}', [App\Http\Controllers\Platform\SuperAdminController::class, 'switchTenant'])->name('platform.switch');
    Route::post('/platform/provision', [App\Http\Controllers\Platform\SuperAdminController::class, 'provision'])->name('platform.provision');

    // Marketplace & Third-Party Integrations Hub
    Route::get('/marketplace', [App\Http\Controllers\Platform\MarketplaceController::class, 'index'])->name('marketplace.index');
    Route::post('/marketplace/wave/sync', [App\Http\Controllers\Platform\MarketplaceController::class, 'syncWave'])->name('marketplace.wave.sync');
    Route::post('/marketplace/shopify/connect', [App\Http\Controllers\Platform\MarketplaceController::class, 'connectShopify'])->name('marketplace.shopify.connect');
});

