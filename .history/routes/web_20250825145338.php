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

        // Contoh route yang benar
Route::get('/report-activity/export-pdf', [ReportActivityController::class, 'exportPdf'])->name('report.activity.pdf');

// Atau jika menggunakan resource route:
Route::resource('report-activity', ReportActivityController::class);
// Tambahkan route khusus untuk export
Route::get('/report-activity/export-pdf', [ReportActivityController::class, 'exportPdf']);

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
    // Route untuk Input Data Pelanggan
    Route::prefix('report/operational')->name('report.operational.')->group(function () {
    Route::get('/', [OperationalReportController::class, 'index'])->name('index');
    Route::post('/', [OperationalReportController::class, 'store'])->name('store');
    Route::get('/show', [OperationalReportController::class, 'show'])->name('show');
    Route::put('/{pelanggan}', [OperationalReportController::class, 'update'])->name('update');
    Route::delete('/{pelanggan}', [OperationalReportController::class, 'destroy'])->name('destroy');
});

Route::get('/customer/search', function() {
    return view('customer.search');
})->name('customer.search');

// Route untuk pencarian customer (jika ada)
Route::get('/customer/search', function() {
    return view('customer.search'); // Buat view ini jika belum ada
})->name('customer.search');

        // Route untuk Cari Pelanggan & Kode FAT
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/search', [App\Http\Controllers\CustomerSearchController::class, 'index'])->name('search');
        Route::put('/update/{id}', [App\Http\Controllers\CustomerSearchController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [App\Http\Controllers\CustomerSearchController::class, 'destroy'])->name('destroy');

        // Route tambahan untuk fitur advanced
        Route::get('/search/fat', [App\Http\Controllers\CustomerSearchController::class, 'searchByFAT'])->name('search.fat');
        Route::post('/search/advanced', [App\Http\Controllers\CustomerSearchController::class, 'advancedSearch'])->name('search.advanced');
        Route::get('/export', [App\Http\Controllers\CustomerSearchController::class, 'exportSearch'])->name('export');
        Route::get('/statistics', [App\Http\Controllers\CustomerSearchController::class, 'getStatistics'])->name('statistics');
    });

        // ================== OTHER UTILITY ROUTES ================== //
        Route::get('/debug-images', [ReportController::class, 'debugImages'])->name('debug.images');
        Route::get('/debug-storage', [ReportActivityController::class, 'debugStorage']);
        Route::get('/fix-storage', [ReportActivityController::class, 'fixStorage']);
    });

// ================== FALLBACK ROUTE ================== //
Route::fallback(function () {
    return redirect()->route('login');
});
