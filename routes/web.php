<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\ProfileCompletionController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use Illuminate\Support\Facades\Route;

// ─── Home ─────────────────────────────────────────────────────────────────────
Route::get('/', function () {
    $trainings = \App\Models\Training::published()
        ->with('category')
        ->withCount('enrollments')
        ->latest()
        ->limit(8)
        ->get();
    return view('home', compact('trainings'));
})->name('home');

// ─── Catalog (Public) ─────────────────────────────────────────────────────────
Route::get('/pelatihan', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/pelatihan/{slug}', [CatalogController::class, 'show'])->name('catalog.show');

// ─── Google OAuth ─────────────────────────────────────────────────────────────
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

// ─── Auth routes (Breeze) ─────────────────────────────────────────────────────
require __DIR__ . '/auth.php';

// ─── Profile completion (before profile_completed middleware) ──────────────────
Route::middleware('auth')->group(function () {
    Route::get('/complete-profile', [ProfileCompletionController::class, 'show'])
        ->name('profile.complete');
    Route::post('/complete-profile', [ProfileCompletionController::class, 'store'])
        ->name('profile.complete.store');
});

// ─── Authenticated & profile-completed routes ─────────────────────────────────
Route::middleware(['auth', 'profile.completed'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/skp', function () {
        $user       = auth()->user();
        if (!$user->isNakes()) abort(403);
        $skpRecords = \App\Models\SkpRecord::with('training.category')
            ->where('user_id', $user->id)
            ->orderByDesc('completed_at')
            ->get();
        $totalSkp   = $skpRecords->sum('skp_earned');
        return view('dashboard.skp', compact('skpRecords', 'totalSkp'));
    })->name('dashboard.skp');

    Route::get('/dashboard/profil', [DashboardController::class, 'profile'])->name('dashboard.profile');
    Route::patch('/dashboard/profil', [DashboardController::class, 'updateProfile'])->name('dashboard.profile.update');

    // Enrollments
    Route::get('/daftar/{training}', [EnrollmentController::class, 'checkout'])
        ->name('enrollments.checkout');
    Route::post('/daftar/{training}/free', [EnrollmentController::class, 'checkout'])
        ->name('enrollments.free');
    Route::post('/daftar/{training}/bayar', [EnrollmentController::class, 'pay'])
        ->name('enrollments.pay');
    Route::post('/daftar/{training}/skip-payment', [EnrollmentController::class, 'skipPayment'])
        ->name('enrollments.skip');
    Route::get('/daftar/{training}/berhasil', [EnrollmentController::class, 'success'])
        ->name('enrollments.success');
});

// ─── Midtrans notification (webhook, no CSRF) ─────────────────────────────────
Route::post('/payment/notification', [EnrollmentController::class, 'notification'])
    ->name('payment.notification')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
