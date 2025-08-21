<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome');
});


// Dashboard
Route::get('/dashboard', [dashboardController::class, 'index'])->name('dashboard');

// Report Activity
Route::get('/reports/activity', [ReportController::class, 'activity'])->name('reports.activity');

// Report Competitor
Route::get('/reports/competitor', [ReportController::class, 'competitor'])->name('reports.competitor');
