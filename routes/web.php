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

Route::get('/switch-from-xero', function () {
    return view('landing.switch-from-xero');
})->name('landing.switch-from-xero');

Route::get('/switch-from-quickbooks', function () {
    return view('landing.switch-from-quickbooks');
})->name('landing.switch-from-quickbooks');

// Target Persona & Vertical Landing Pages
Route::prefix('for')->group(function () {
    Route::get('/gst-registered-companies', function () {
        return view('landing.for.gst-registered-companies');
    })->name('landing.for.gst');

    Route::get('/freelancers-consultants', function () {
        return view('landing.for.freelancers-consultants');
    })->name('landing.for.freelancers');

    Route::get('/digital-agencies', function () {
        return view('landing.for.digital-agencies');
    })->name('landing.for.agencies');

    Route::get('/ecommerce-merchants', function () {
        return view('landing.for.ecommerce-merchants');
    })->name('landing.for.ecommerce');

    Route::get('/wholesale-distributors', function () {
        return view('landing.for.wholesale-distributors');
    })->name('landing.for.wholesale');

    Route::get('/corporate-secretaries', function () {
        return view('landing.for.corporate-secretaries');
    })->name('landing.for.sec');
});

// Client Portal & Instant PayNow QR Checkout Experience (Track 6)
Route::get('/pay/{document_number}', [App\Http\Controllers\Portal\PayNowCheckoutController::class, 'show'])->name('portal.paynow.checkout');
Route::post('/pay/{document_number}/confirm', [App\Http\Controllers\Portal\PayNowCheckoutController::class, 'confirmPayment'])->name('portal.paynow.confirm');

// Straits Master Telemetry Endpoint (CORS enabled for straits.thethinkthank.com)
Route::get('/api/platform/telemetry', [App\Http\Controllers\Api\PlatformTelemetryController::class, 'getTelemetry']);

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

