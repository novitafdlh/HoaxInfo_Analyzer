<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminSubmissionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserOfficialContentController;
use App\Http\Controllers\UserValidationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicMediaController;
use App\Http\Controllers\OfficialContentController;
use Illuminate\Support\Facades\Route;

// Landing / Dashboard untuk semua (guest + auth)
Route::redirect('/', '/dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/dashboard/upload', [DashboardController::class, 'store'])->name('dashboard.upload');
Route::get('/media/public/{path}', [PublicMediaController::class, 'show'])
    ->where('path', '.*')
    ->name('media.public');

// ADMIN
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/submissions', [AdminSubmissionController::class, 'index'])->name('admin.submissions.index');
    Route::get('/admin/submissions/{submission}', [AdminSubmissionController::class, 'show'])->name('admin.submissions.show');
    Route::patch('/admin/submissions/{submission}/final-status', [AdminSubmissionController::class, 'updateFinalStatus'])
        ->name('admin.submissions.update-status');

    Route::get('/official', [OfficialContentController::class, 'index'])->name('official.index');
    Route::get('/official/create', [OfficialContentController::class, 'create'])->name('official.create');
    Route::post('/official/store', [OfficialContentController::class, 'store'])->name('official.store');
    Route::delete('/official/{officialContent}', [OfficialContentController::class, 'destroy'])->name('official.destroy');
});

// USER
Route::middleware(['auth', 'verified', 'role:user'])->group(function () {
    Route::view('/user/dashboard', 'user.dashboard')->name('user.dashboard');
    Route::get('/user/validation-results', [UserValidationController::class, 'index'])->name('user.validation-results');
    Route::get('/user/official-contents', [UserOfficialContentController::class, 'index'])->name('user.official.index');
    Route::get('/user/official-contents/{officialContent}', [UserOfficialContentController::class, 'show'])->name('user.official.show');
});

// PROFILE (semua user login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
