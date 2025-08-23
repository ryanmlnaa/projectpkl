<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;

// Dashboard umum (default)
Route::get('/dashboard', [dashboardController::class, 'index'])->name('dashboard');

// Report Activity
Route::get('/reports/activity', [ReportController::class, 'activity'])->name('reports.activity');
Route::get('/reports/competitor', [ReportController::class, 'competitor'])->name('reports.competitor');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
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


  // Forgot password routes - URUTAN YANG BENAR
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot.password');
    Route::post('/send-reset-code', [AuthController::class, 'sendResetCode'])->name('send.reset.code');
    Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('reset.password');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset.password.post');

// Route untuk user yang sudah login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard routes
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Admin routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
    });
});

// Root redirect
Route::get('/', function () {
    return redirect()->route('login');
});