<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompetitorController;
use App\Http\Controllers\OperationalReportController;
use App\Http\Controllers\ReportActivityController;

// ================== ROOT REDIRECT ================== //
Route::get('/', function () {
    return redirect()->route('login');
});

// ================== AUTH ROUTES (PUBLIC) ================== //
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Forgot password
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot.password');
Route::post('/send-reset-code', [AuthController::class, 'sendResetCode'])->name('send.reset.code');
Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('reset.password');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset.password.post');

// ================== PROTECTED ROUTES (REQUIRE AUTH) ================== //
Route::middleware('auth')->group(function () {

    // ================== DASHBOARD ROUTES ================== //
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Role-based dashboard
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->middleware('role:admin')->name('admin.dashboard');

    Route::get('/user/dashboard', function () {
        return view('dashboard');
    })->middleware('role:user')->name('user.dashboard');

    // ================== REPORT ACTIVITY ROUTES ================== //
    Route::prefix('reports')->name('reports.')->group(function () {

        // Report Activity CRUD
        Route::get('/activity', [ReportActivityController::class, 'index'])->name('activity');
        Route::post('/activity', [ReportActivityController::class, 'store'])->name('store');
        Route::get('/activity/{id}/edit', [ReportActivityController::class, 'edit'])->name('edit');
        Route::put('/activity/{id}', [ReportActivityController::class, 'update'])->name('update');
        Route::delete('/activity/{id}', [ReportActivityController::class, 'destroy'])->name('destroy');

        // Export routes
        Route::get('/activity/export-pdf', [ReportActivityController::class, 'exportPdf'])->name('exportPdf');
        Route::get('/activity/export-csv', [ReportActivityController::class, 'exportCsv'])->name('exportCsv');
        Route::get('/activity/export', [ReportActivityController::class, 'export'])->name('export');
        Route::get('/activity/print', [ReportActivityController::class, 'printView'])->name('print');

        // Report Competitor (view saja) - menggunakan ReportController
        Route::get('/competitor', [ReportController::class, 'competitor'])->name('competitor');

        // Other report routes (jika ada)
        Route::get('/export/pdf', [ReportController::class, 'exportPdf'])->name('export.pdf');

        // Debug routes (hapus setelah masalah selesai)
        Route::get('/debug', [ReportController::class, 'debugData'])->name('debug');
        Route::get('/refresh', [ReportController::class, 'refresh'])->name('refresh');
    });

    // ================== COMPETITOR ROUTES ================== //
    Route::resource('competitor', CompetitorController::class);

    // ================== OPERATIONAL REPORT ROUTES ================== //
    Route::prefix('reports/operational')->name('reports.operational.')->group(function () {
        Route::get('/', [OperationalReportController::class, 'index'])->name('index');
        Route::post('/', [OperationalReportController::class, 'store'])->name('store');
        Route::put('/{pelanggan}', [OperationalReportController::class, 'update'])->name('update');
        Route::delete('/{pelanggan}', [OperationalReportController::class, 'destroy'])->name('destroy');

        // Uncomment jika diperlukan di masa depan
        // Route::get('/input', [OperationalReportController::class, 'input'])->name('input');
        // Route::post('/input', [OperationalReportController::class, 'storeInput'])->name('input.store');
        // Route::get('/search', [OperationalReportController::class, 'search'])->name('search');
        // Route::get('/fat', [OperationalReportController::class, 'fat'])->name('fat');
        // Route::post('/fat', [OperationalReportController::class, 'storeFat'])->name('fat.store');
    });

    // ================== OTHER UTILITY ROUTES ================== //
    Route::get('/debug-images', [ReportController::class, 'debugImages'])->name('debug.images');
    Route::get('/debug-storage', [ReportActivityController::class, 'debugStorage']);
});

// ================== FALLBACK ROUTE ================== //
Route::fallback(function () {
    return redirect()->route('login');
});
