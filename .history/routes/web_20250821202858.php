<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('dashboard.index');
});

Route::get('/dashboard', [dashboardController::class, 'index'])->name('dashboard.index');
