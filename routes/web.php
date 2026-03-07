<?php

use App\Http\Controllers\Apps\CashManagementController;
use App\Http\Controllers\Apps\PermissionController;
use App\Http\Controllers\Apps\RoleController;
use App\Http\Controllers\Apps\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['prefix' => 'apps', 'as' => 'apps.' , 'middleware' => ['auth']], function(){
    // dashboard route
    Route::get('/dashboard', [CashManagementController::class, 'dashboard'])->name('dashboard');

    Route::prefix('/cash-management')->as('cash-management.')->group(function () {
        Route::get('/master-data', [CashManagementController::class, 'masterData'])->name('master-data');
        Route::get('/transactions', [CashManagementController::class, 'transactions'])->name('transactions');
        Route::get('/approvals', [CashManagementController::class, 'approvals'])->name('approvals');
        Route::get('/treasury', [CashManagementController::class, 'treasury'])->name('treasury');
        Route::get('/reconciliation', [CashManagementController::class, 'reconciliation'])->name('reconciliation');
        Route::get('/reports', [CashManagementController::class, 'reports'])->name('reports');
        Route::get('/administration', [CashManagementController::class, 'administration'])->name('administration');
    });
    // permissions route
    Route::get('/permissions', PermissionController::class)->name('permissions.index');
    // roles route
    Route::resource('/roles', RoleController::class)->except(['create', 'edit', 'show']);
    // users route
    Route::resource('/users', UserController::class)->except('show');
});

require __DIR__.'/auth.php';
