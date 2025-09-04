<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompetitorController;
use App\Http\Controllers\OperationalReportController;
use App\Http\Controllers\ReportActivityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\CustomerSearchController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ExportCompetitorController;
use App\Http\Controllers\ExportActivityController;

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

        // route user
        })->middleware('role:user')->name('user.dashboard');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

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

    // ========== MISSING API ENDPOINTS - ADD THESE ========== //
    Route::get('/get-kabupaten', [OperationalReportController::class, 'getKabupaten'])->name('get-kabupaten');
    Route::get('/get-kode-fat', [OperationalReportController::class, 'getKodeFat'])->name('get-kode-fat');
    });
    // Route::get('/customer/search', function() {
    //     return view('customer.search');
    // })->name('customer.search');

    // Perbaiki route pertama (typo: repot -> report)
    Route::get('/report/customer/search', function() {
        return view('report.customer.search'); // Buat view ini jika belum ada
    })->name('report.customer.search');

    // Route untuk Customer dengan middleware auth
    Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    // Route untuk search customer
    Route::get('/search', [App\Http\Controllers\CustomerSearchController::class, 'index'])->name('search');

    // Route untuk edit customer - perbaiki pattern URL
    Route::get('/{id}/edit', [App\Http\Controllers\CustomerSearchController::class, 'edit'])->name('edit');
    Route::put('/{id}', [App\Http\Controllers\CustomerSearchController::class, 'update'])->name('update');
    Route::delete('/{id}', [App\Http\Controllers\CustomerSearchController::class, 'destroy'])->name('destroy');

    // Map and Location Routes - perbaiki URL pattern
    Route::get('/map', [App\Http\Controllers\CustomerSearchController::class, 'showMap'])->name('map');

    // API Routes for dropdown data
    Route::get('/api/provinsi', [App\Http\Controllers\CustomerSearchController::class, 'getProvinsi'])->name('api.provinsi');
    Route::get('/api/kabupaten', [App\Http\Controllers\CustomerSearchController::class, 'getKabupaten'])->name('api.kabupaten');
    Route::get('/api/statistics', [App\Http\Controllers\CustomerSearchController::class, 'getStatistics'])->name('api.statistics');

    Route::get('/search/fat', [App\Http\Controllers\CustomerSearchController::class, 'searchByFAT'])->name('search.fat');
    Route::get('/search/advanced', [App\Http\Controllers\CustomerSearchController::class, 'advancedSearch'])->name('search.advanced');
    Route::get('/statistics', [App\Http\Controllers\CustomerSearchController::class, 'getStatistics'])->name('statistics');
    Route::post('/export', [App\Http\Controllers\CustomerSearchController::class, 'exportSearch'])->name('export');

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

 Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');

    // Debug route (hapus setelah selesai debug)
    Route::get('/test-user', function() {
        $user = Auth::user();
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'profile_photo_path' => $user->profile_photo_path,
            'file_exists_storage' => $user->profile_photo_path ? file_exists(storage_path('app/public/' . $user->profile_photo_path)) : false,
            'storage_url' => $user->profile_photo_path ? Storage::url($user->profile_photo_path) : null,
        ];
    });
    Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])
    ->name('profile.change.password');



Route::prefix('export')->group(function () {
    // Activity
    Route::get('/activity', [ExportController::class, 'activityView'])->name('export.activity');
    Route::get('/activity/pdf', [ExportController::class, 'exportActivityPdf'])->name('export.activity.pdf');
    Route::get('/activity/csv', [ExportController::class, 'exportActivityCsv'])->name('export.activity.csv');
    Route::get('/export/activity/excel', [ExportActivityController::class, 'exportExcel'])->name('export.activity.excel');

    //Competitor
    Route::get('/export/competitor', [ExportCompetitorController::class, 'index'])->name('export.competitor');
    Route::get('/export/competitor/pdf', [ExportCompetitorController::class, 'exportPdf'])->name('export.competitor.pdf');
    Route::get('/export/competitor/csv', [ExportCompetitorController::class, 'exportCsv'])->name('export.competitor.csv');
    Route::get('/export/competitor/excel', [ExportCompetitorController::class, 'exportExcel'])->name('export.competitor.excel');

    // Operational
    Route::get('/operational', [ExportController::class, 'operationalView'])->name('export.operational');
    Route::get('/operational/pdf', [ExportController::class, 'exportOperationalPdf'])->name('export.operational.pdf');
    Route::get('/operational/csv', [ExportController::class, 'exportOperationalCsv'])->name('export.operational.csv');
});


// ================== FALLBACK ROUTE ================== //
Route::fallback(function () {
    return redirect()->route('login');
});

Route::prefix('customer')->group(function () {
    Route::get('/search', [CustomerSearchController::class, 'index'])->name('customer.search');
    Route::get('/search/advanced', [CustomerSearchController::class, 'advancedSearch'])->name('customer.search.advanced');
    Route::get('/search/{id}/edit', [CustomerSearchController::class, 'edit'])->name('customer.edit');
    Route::put('/search/{id}', [CustomerSearchController::class, 'update'])->name('customer.update');
    Route::delete('/search/{id}', [CustomerSearchController::class, 'destroy'])->name('customer.delete');
    Route::get('/map', [CustomerSearchController::class, 'showMap'])->name('customer.map');
});

Route::get('/get-kabupaten', [App\Http\Controllers\OperationalReportController::class, 'getKabupaten']);
Route::get('/get-kode-fat', [App\Http\Controllers\OperationalReportController::class, 'getKodeFat']);

Route::get('/api/kecepatan-by-bandwidth', [OperationalReportController::class, 'getKecepatanByBandwidth']);
Route::get('/get-kecepatan/{cluster}', [App\Http\Controllers\CompetitorController::class, 'getKecepatan']);
Route::get('/get-kecepatan-by-bandwidth', [App\Http\Controllers\CompetitorController::class, 'getKecepatanByBandwidth']);
Route::get('/get-kecepatan', [OperationalReportController::class, 'getKecepatanByBandwidth']);
Route::get('/get-kecepatan', [App\Http\Controllers\CompetitorController::class, 'getKecepatanByBandwidth'])->name('get.kecepatan');
Route::get('/operational-report', [OperationalReportController::class, 'index'])->name('operational.index');
Route::get('/get-kecepatan', [OperationalReportController::class, 'getKecepatan'])->name('get.kecepatan');






