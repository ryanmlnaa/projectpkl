<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompetitorController;
use App\Http\Controllers\OperationalReportController;

// Dashboard umum (default)
Route::get('/dashboard', [dashboardController::class, 'index'])->name('dashboard');

// Report Activity
Route::get('/reports/activity', [ReportController::class, 'activity'])->name('reports.activity');
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
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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
