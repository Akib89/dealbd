<?php
use App\Http\Controllers\{HomeController, AuthController, DealController, VoteController, CommentController,
    BookmarkController, ReportController, DealAlertController, DashboardController,
    NotificationController, VisitController, MerchantController};
use App\Http\Controllers\Admin\ModerationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1')->name('login.store');
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1')->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/deals/create', [DealController::class, 'create'])->name('deals.create');
    Route::post('/deals', [DealController::class, 'store'])->middleware('throttle:5,1')->name('deals.store');
    Route::get('/deals/{deal:slug}/edit', [DealController::class, 'edit'])->name('deals.edit');
    Route::put('/deals/{deal:slug}', [DealController::class, 'update'])->name('deals.update');
    Route::post('/deals/{deal:slug}/vote', [VoteController::class, 'store'])->middleware('throttle:60,1')->name('deals.vote');
    Route::post('/deals/{deal:slug}/comments', [CommentController::class, 'store'])->middleware('throttle:12,1')->name('comments.store');
    Route::post('/deals/{deal:slug}/bookmark', [BookmarkController::class, 'toggle'])->name('deals.bookmark');
    Route::post('/deals/{deal:slug}/report', [ReportController::class, 'store'])->middleware('throttle:8,1')->name('deals.report');
    Route::get('/alerts', [DealAlertController::class, 'index'])->name('alerts.index');
    Route::post('/alerts', [DealAlertController::class, 'store'])->name('alerts.store');
    Route::delete('/alerts/{alert}', [DealAlertController::class, 'destroy'])->name('alerts.destroy');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::get('/merchant', [MerchantController::class, 'index'])->name('merchant.index');
});

Route::middleware(['auth', 'can:moderate-deals'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [ModerationController::class, 'index'])->name('index');
    Route::post('/deals/{deal}/moderate', [ModerationController::class, 'moderate'])->name('moderate');
    Route::post('/reports/{report}/resolve', [ModerationController::class, 'resolveReport'])->name('reports.resolve');
    Route::post('/comments/{comment}/hide', [ModerationController::class, 'hideComment'])->name('comments.hide');
    Route::post('/users/{user}/role', [ModerationController::class, 'setRole'])->middleware('can:manage-users')->name('users.role');
});

// Keep wildcard deal routes AFTER /deals/create and /deals/{deal}/edit.
Route::get('/deals/{deal:slug}', [DealController::class, 'show'])->name('deals.show');
Route::get('/deals/{deal:slug}/visit', [VisitController::class, 'go'])->name('deals.visit');
