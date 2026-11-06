<?php

use App\Http\Controllers\Admin\ModerationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DealAlertController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login')->name('login.store');
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:registration')->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/deals/create', [DealController::class, 'create'])->name('deals.create');
    Route::post('/deals', [DealController::class, 'store'])->middleware('throttle:deal-submission')->name('deals.store');
    Route::get('/deals/{deal:slug}/edit', [DealController::class, 'edit'])->name('deals.edit');
    Route::put('/deals/{deal:slug}', [DealController::class, 'update'])->middleware('throttle:deal-submission')->name('deals.update');
    Route::post('/deals/{deal:slug}/vote', [VoteController::class, 'store'])->middleware('throttle:community-write')->name('deals.vote');
    Route::post('/deals/{deal:slug}/comments', [CommentController::class, 'store'])->middleware('throttle:community-write')->name('comments.store');
    Route::post('/deals/{deal:slug}/bookmark', [BookmarkController::class, 'toggle'])->middleware('throttle:community-write')->name('deals.bookmark');
    Route::post('/deals/{deal:slug}/report', [ReportController::class, 'store'])->middleware('throttle:reporting')->name('deals.report');
    Route::get('/alerts', [DealAlertController::class, 'index'])->name('alerts.index');
    Route::post('/alerts', [DealAlertController::class, 'store'])->middleware('throttle:community-write')->name('alerts.store');
    Route::delete('/alerts/{alert}', [DealAlertController::class, 'destroy'])->middleware('throttle:community-write')->name('alerts.destroy');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->middleware('throttle:community-write')->name('notifications.read');
    Route::get('/merchant', [MerchantController::class, 'index'])->name('merchant.index');
});

Route::middleware(['auth', 'can:moderate-deals'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [ModerationController::class, 'index'])->name('index');
    Route::get('/users/{user}/edit', [ModerationController::class, 'editUser'])->middleware('can:manage-users')->name('users.edit');
    Route::put('/users/{user}', [ModerationController::class, 'updateUser'])->middleware(['can:manage-users', 'throttle:admin-write'])->name('users.update');
    Route::post('/deals/{deal}/moderate', [ModerationController::class, 'moderate'])->middleware('throttle:admin-write')->name('moderate');
    Route::post('/reports/{report}/resolve', [ModerationController::class, 'resolveReport'])->middleware('throttle:admin-write')->name('reports.resolve');
    Route::post('/comments/{comment}/hide', [ModerationController::class, 'hideComment'])->middleware('throttle:admin-write')->name('comments.hide');
    Route::post('/users/{user}/role', [ModerationController::class, 'setRole'])->middleware(['can:manage-users', 'throttle:admin-write'])->name('users.role');
});

// Keep wildcard deal routes AFTER /deals/create and /deals/{deal}/edit.
Route::get('/deals/{deal:slug}', [DealController::class, 'show'])->name('deals.show');
Route::get('/deals/{deal:slug}/visit', [VisitController::class, 'go'])->name('deals.visit');
