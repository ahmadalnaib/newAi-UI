<?php

use Inertia\Inertia;
use App\Http\Controllers\Dashboard;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\CodeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\PrivacyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ComponentController;
use App\Http\Middleware\RedirectIfCancelled;
use App\Http\Middleware\RedirectIfSubscribed;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\RefundController;
use App\Http\Middleware\RedirectIfNotCancelled;
use App\Http\Controllers\TailwindCodeController;
use App\Http\Middleware\RedirectIfNotSubscribed;

// home
Route::get('/', HomeController::class)->name('home');
Route::post('/', [HomeController::class, 'generateWithFreeAI'])->name('generateWithFreeAI');

//plans

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/plans', [PlanController::class, 'index'])->name('plans');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
});


// design routes
Route::get('/design', [ComponentController::class, 'index'])->name('design');
Route::get('/design/{tailwindCode:slug}', [ComponentController::class, 'show'])->name('design.show');


// Tailwind code routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [TailwindCodeController::class, 'index'])->name('dashboard');
    Route::get('/ui/create', [TailwindCodeController::class, 'create'])
        ->middleware(RedirectIfNotSubscribed::class)
        ->name('ui.create');
    Route::post('/ui', [TailwindCodeController::class, 'store'])->name('ui.store');
    Route::get('/ui/{tailwindCode:slug}', [TailwindCodeController::class, 'edit'])->name('ui.edit');
    Route::delete('/ui/{tailwindCode:slug}', [TailwindCodeController::class, 'destroy'])->name('ui.destroy');
});



// AI routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/codegenerate', function () {
        return Inertia::render('Tailwind/Index');
    });
    Route::post('/codegenerate', [TailwindCodeController::class, 'generateSnippetWithAI'])->name('code.generate');
    Route::post('/editgenerate', [TailwindCodeController::class, 'editgenerate']);
});


Route::middleware(['auth','verified'])->group(function () {
    Route::get('/membership', [MembershipController::class, 'index'])->name('membership.index');
    Route::get('/membership/portal', [MembershipController::class, 'portal'])
    ->middleware(RedirectIfNotSubscribed::class)->name('membership.portal');
    Route::post('/membership/resume', [MembershipController::class, 'resume'])
    ->middleware('subscribed')
    ->name('membership.resume');
    Route::post('/membership/cancel', [MembershipController::class, 'cancel'])
    ->middleware(RedirectIfCancelled::class)
    ->name('membership.cancel');
    // add middleware if not stripe user
    // check the User Observer
    Route::get('/membership/invoice', [MembershipController::class, 'invoice'])->name('membership.invoice');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/terms', TermsController::class)->name('terms');
Route::get('/privacy', PrivacyController::class)->name('privacy');
Route::get('/refund',RefundController::class)->name('refund');

require __DIR__ . '/auth.php';
