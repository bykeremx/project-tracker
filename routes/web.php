<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EarningsController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ProjectUpdateController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Status\ProjectStatusController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('login.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::get('earnings', [EarningsController::class, 'index'])->name('earnings.index');
    Route::get('earnings/{year}/{month}', [EarningsController::class, 'show'])
        ->where(['year' => '[0-9]{4}', 'month' => '0?[1-9]|1[0-2]'])
        ->name('earnings.show');

    Route::resource('admins', AdminUserController::class)
        ->parameters(['admins' => 'user'])
        ->except(['show'])
        ->middlewareFor(['store', 'update', 'destroy'], 'throttle:admin-writes');

    Route::resource('clients', ClientController::class)
        ->except(['show'])
        ->middlewareFor(['store', 'update', 'destroy'], 'throttle:admin-writes');

    Route::resource('projects', ProjectController::class)
        ->middlewareFor(['store', 'update', 'destroy'], 'throttle:admin-writes');

    Route::patch('projects/{project}/status', [ProjectController::class, 'updateStatus'])
        ->middleware('throttle:admin-writes')
        ->name('projects.status');

    Route::post('projects/{project}/updates', [ProjectUpdateController::class, 'store'])
        ->middleware('throttle:admin-writes')
        ->name('projects.updates.store');

    Route::patch('projects/{project}/updates/{update}', [ProjectUpdateController::class, 'update'])
        ->middleware('throttle:admin-writes')
        ->name('projects.updates.update');

    Route::post('projects/{project}/payments', [PaymentController::class, 'store'])
        ->middleware('throttle:admin-writes')
        ->name('projects.payments.store');

    Route::delete('projects/{project}/payments/{payment}', [PaymentController::class, 'destroy'])
        ->middleware('throttle:admin-writes')
        ->name('projects.payments.destroy');
});

Route::get('/status/{access_token}', [ProjectStatusController::class, 'show'])
    ->where('access_token', '[A-Za-z0-9]{64}')
    ->name('status.show');

Route::get('/status/{access_token}/updates', [ProjectStatusController::class, 'updates'])
    ->where('access_token', '[A-Za-z0-9]{64}')
    ->name('status.updates');
