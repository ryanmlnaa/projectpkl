<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\CompetitorController;
use App\Http\Controllers\OperationalReportController;

use App\Http\Controllers\ReportActivityController;

// ================== DASHBOARD ================== //
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// ================== REPORT ================== //
// Competitor report tetap pakai ReportController
Route::get('/reports/competitor', [ReportController::class, 'competitor'])->name('reports.competitor');

// Report Competitor
Route::resource('competitor', CompetitorController::class);

// // Halaman Operational Report (poin 1, 2, 3)
Route::get('/reports/operational', [OperationalReportController::class, 'index'])->name('reports.operational'); // view + search
Route::post('/reports/operational', [OperationalReportController::class, 'store'])->name('reports.operational.store'); // simpan
Route::put('/reports/operational/{pelanggan}', [OperationalReportController::class, 'update'])->name('reports.operational.update'); // update
Route::delete('/reports/operational/{pelanggan}', [OperationalReportController::class, 'destroy'])->name('reports.operational.destroy'); // delete

// Route::prefix('reports/operational')->name('reports.operational.')->group(function () {
//     Route::get('/input', [OperationalReportController::class, 'input'])->name('input');
//     Route::post('/input', [OperationalReportController::class, 'storeInput'])->name('store');

//     Route::get('/search', [OperationalReportController::class, 'search'])->name('search');

//     Route::get('/fat', [OperationalReportController::class, 'fat'])->name('fat');
//     Route::post('/fat', [OperationalReportController::class, 'storeFat'])->name('fat.store');
// });

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Redirect root "/" ke login
Route::get('/', function () {
    return redirect()->route('login');
});


// Forgot password
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot.password');
Route::post('/send-reset-code', [AuthController::class, 'sendResetCode'])->name('send.reset.code');
Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('reset.password');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset.password.post');

// ================== ROLE-BASED DASHBOARD ================== //
Route::middleware('auth')->group(function () {
    // khusus admin
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->middleware('role:admin')->name('admin.dashboard');

    // khusus user
    Route::get('/user/dashboard', function () {
        return view('dashboard');
    })->middleware('role:user')->name('user.dashboard');
});

// ================== ROOT REDIRECT ================== //
Route::get('/', function () {
    return redirect()->route('login');
});

// CRUD Report Activity
Route::get('reports/activity', [ReportActivityController::class, 'index'])->name('reports.activity');
Route::post('reports/activity', [ReportActivityController::class, 'store'])->name('reports.store');
Route::get('reports/activity/{id}/edit', [ReportActivityController::class, 'edit'])->name('reports.edit');
Route::put('reports/activity/{id}', [ReportActivityController::class, 'update'])->name('reports.update');
Route::delete('reports/activity/{id}', [ReportActivityController::class, 'destroy'])->name('reports.destroy');
Route::resource('reports', ReportController::class);
Route::get('reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.exportPdf');
Route::get('/debug-images', [ReportController::class, 'debugImages']);

// Report Competitor (view saja)
Route::get('reports/competitor', [ReportController::class, 'competitor'])->name('reports.competitor');

// Route debugging (hapus setelah masalah selesai)
Route::get('/reports/debug', [ReportController::class, 'debugData'])->name('reports.debug');
Route::get('/reports/refresh', [ReportController::class, 'refresh'])->name('reports.refresh');
