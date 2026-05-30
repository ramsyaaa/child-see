<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Auth\UnifiedAuthController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Superadmin\DashboardController as SuperadminDashboardController;
use App\Http\Controllers\Superadmin\ContentController;
use App\Http\Controllers\Superadmin\MemberController;
use App\Http\Controllers\Superadmin\CategoryController;
use App\Http\Controllers\Superadmin\DomainController;
use App\Http\Controllers\Superadmin\QuestionnaireController;
use App\Http\Controllers\Superadmin\AssessmentRuleController;
use App\Http\Controllers\Superadmin\AssessmentAdminController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\ChildController;
use App\Http\Controllers\Member\AssessmentController;
use App\Http\Controllers\Member\ProfileController as MemberProfileController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ============================================
// CHILD SEE PUBLIC ROUTES
// ============================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
/*
|--------------------------------------------------------------------------
| FERENSA STUDIO - UNIFIED AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/

// Unified Login & Registration
Route::get('/login', [UnifiedAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UnifiedAuthController::class, 'login'])->name('login.submit');
Route::get('/register', [UnifiedAuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [UnifiedAuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [UnifiedAuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [UnifiedAuthController::class, 'showForgotPasswordForm'])->name('password.request');

/*
|--------------------------------------------------------------------------
| FERENSA STUDIO - FITNESS BOOKING SYSTEM ROUTES
|--------------------------------------------------------------------------
*/

// ============================================
// SUPERADMIN ROUTES
// ============================================
Route::prefix('superadmin')->name('superadmin.')->group(function () {
    // Protected routes
    Route::middleware(['auth', 'role:superadmin'])->group(function () {
        Route::get('/dashboard', [SuperadminDashboardController::class, 'index'])->name('dashboard');

        // Child See — Master Data
        Route::resource('categories', CategoryController::class);
        Route::resource('domains', DomainController::class);
        Route::resource('questionnaires', QuestionnaireController::class);
        Route::resource('rules', AssessmentRuleController::class);

        // Child See — Assessments (read-only admin view)
        Route::get('/assessments', [AssessmentAdminController::class, 'index'])->name('assessments.index');
        Route::get('/assessments/{assessment}', [AssessmentAdminController::class, 'show'])->name('assessments.show');

        // Members
        Route::get('/members', [MemberController::class, 'index'])->name('members.index');
        Route::get('/members/{member}', [MemberController::class, 'show'])->name('members.show');
        Route::get('/members/{member}/edit', [MemberController::class, 'edit'])->name('members.edit');
        Route::put('/members/{member}', [MemberController::class, 'update'])->name('members.update');
        Route::patch('/members/{member}/toggle-status', [MemberController::class, 'toggleStatus'])->name('members.toggle-status');

        // Content Management (CMS)
        Route::get('/content', [ContentController::class, 'index'])->name('content.index');
        Route::get('/content/{cmsPage}', [ContentController::class, 'show'])->name('content.show');
        Route::put('/content/{cmsPage}/seo', [ContentController::class, 'updateSeo'])->name('content.seo');
        Route::get('/content/{cmsPage}/sections/{section}/edit', [ContentController::class, 'editSection'])->name('content.section.edit');
        Route::put('/content/{cmsPage}/sections/{section}', [ContentController::class, 'updateSection'])->name('content.section.update');

        // Settings
        Route::get('/settings', [\App\Http\Controllers\Superadmin\SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [\App\Http\Controllers\Superadmin\SettingsController::class, 'update'])->name('settings.update');
    });
});

// ============================================
// MEMBER ROUTES
// ============================================
Route::prefix('member')->name('member.')->middleware(['auth', 'role:member'])->group(function () {
    Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');

    // Children
    Route::resource('children', ChildController::class);

    // Assessment flow
    Route::get('/assessment/start', [AssessmentController::class, 'selectChild'])->name('assessment.start');
    Route::post('/assessment/begin', [AssessmentController::class, 'start'])->name('assessment.begin');
    Route::get('/assessment/{assessment}/questions', [AssessmentController::class, 'questions'])->name('assessment.questions');
    Route::post('/assessment/{assessment}/submit', [AssessmentController::class, 'submit'])->name('assessment.submit');
    Route::get('/assessment/{assessment}/result', [AssessmentController::class, 'result'])->name('assessment.result');
    Route::get('/assessment/history', [AssessmentController::class, 'history'])->name('assessment.history');

    // Profile
    Route::get('/profile', [MemberProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [MemberProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [MemberProfileController::class, 'changePassword'])->name('profile.password');
});

// General sign-out route (works for all roles)
Route::post('/sign-out', function() {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('login')->with('success', 'Logged out successfully.');
})->name('sign-out');

