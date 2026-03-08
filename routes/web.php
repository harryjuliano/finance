<?php

use App\Http\Controllers\Apps\CashManagementController;
use App\Http\Controllers\Apps\MasterDataController;
use App\Http\Controllers\Apps\PaymentRequestController;
use App\Http\Controllers\Apps\PhaseOneController;
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

Route::group(['prefix' => 'apps', 'as' => 'apps.', 'middleware' => ['auth']], function () {
    Route::get('/dashboard', [CashManagementController::class, 'dashboard'])->name('dashboard');

    Route::prefix('/cash-management')->as('cash-management.')->group(function () {
        Route::resource('/payment-requests', PaymentRequestController::class)->except(['create', 'edit', 'show']);
        Route::resource('/master-data', MasterDataController::class)->except(['create', 'edit', 'show']);
        Route::post('/payment-requests/{payment_request}/submit', [PaymentRequestController::class, 'submit'])->name('payment-requests.submit');
        Route::post('/payment-requests/{payment_request}/verify', [PaymentRequestController::class, 'verify'])->name('payment-requests.verify');
        Route::post('/payment-requests/{payment_request}/approve', [PaymentRequestController::class, 'approve'])->name('payment-requests.approve');
        Route::post('/payment-requests/{payment_request}/reject', [PaymentRequestController::class, 'reject'])->name('payment-requests.reject');
        Route::post('/payment-requests/{payment_request}/request-revision', [PaymentRequestController::class, 'requestRevision'])->name('payment-requests.request-revision');
        Route::post('/payment-requests/{payment_request}/mark-paid', [PaymentRequestController::class, 'markPaid'])->name('payment-requests.mark-paid');
        Route::get('/phase-1', [PhaseOneController::class, 'index'])->name('phase-1.index');

        Route::get('/approvals', [CashManagementController::class, 'approvals'])->name('approvals');
        Route::get('/treasury', [CashManagementController::class, 'treasury'])->name('treasury');
        Route::get('/reconciliation', [CashManagementController::class, 'reconciliation'])->name('reconciliation');
        Route::get('/reports', [CashManagementController::class, 'reports'])->name('reports');
        Route::get('/administration', [CashManagementController::class, 'administration'])->name('administration');
    });

    Route::get('/permissions', PermissionController::class)->name('permissions.index');
    Route::resource('/roles', RoleController::class)->except(['create', 'edit', 'show']);
    Route::resource('/users', UserController::class)->except('show');
});

require __DIR__.'/auth.php';
