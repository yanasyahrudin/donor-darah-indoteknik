<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\DashboardController;

Route::get('/', [ParticipantController::class, 'create'])->name('form');
Route::post('/register', [ParticipantController::class, 'store'])->name('register');

// ADMIN AUTH
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::get('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// DASHBOARD ADMIN
Route::middleware('admin.auth')->group(function () {
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');
});
Route::post('/admin/store-participant', [DashboardController::class, 'storeParticipant'])->name('admin.storeParticipant');
