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
use App\Http\Controllers\Superadmin\LandingContentController;
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
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('domains', DomainController::class)->except(['show']);
        Route::resource('questionnaires', QuestionnaireController::class)->except(['show']);
        Route::resource('rules', AssessmentRuleController::class)->except(['show']);

        // Child See — Assessments (read-only admin view)
        Route::get('/assessments', [AssessmentAdminController::class, 'index'])->name('assessments.index');
        Route::get('/assessments/{assessment}', [AssessmentAdminController::class, 'show'])->name('assessments.show');

        // Members (unified — covers all roles)
        Route::get('/members', [MemberController::class, 'index'])->name('members.index');
        Route::get('/members/create', [MemberController::class, 'create'])->name('members.create');
        Route::post('/members', [MemberController::class, 'store'])->name('members.store');
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

        // Landing Content Management
        Route::get('/landing', [LandingContentController::class, 'index'])->name('landing.index');
        Route::get('/landing/facts', [LandingContentController::class, 'facts'])->name('landing.facts');
        Route::get('/landing/facts/create', [LandingContentController::class, 'factsCreate'])->name('landing.facts.create');
        Route::post('/landing/facts', [LandingContentController::class, 'factsStore'])->name('landing.facts.store');
        Route::get('/landing/facts/{fact}/edit', [LandingContentController::class, 'factsEdit'])->name('landing.facts.edit');
        Route::put('/landing/facts/{fact}', [LandingContentController::class, 'factsUpdate'])->name('landing.facts.update');
        Route::delete('/landing/facts/{fact}', [LandingContentController::class, 'factsDestroy'])->name('landing.facts.destroy');

        Route::get('/landing/team', [LandingContentController::class, 'team'])->name('landing.team');
        Route::get('/landing/team/create', [LandingContentController::class, 'teamCreate'])->name('landing.team.create');
        Route::post('/landing/team', [LandingContentController::class, 'teamStore'])->name('landing.team.store');
        Route::get('/landing/team/{member}/edit', [LandingContentController::class, 'teamEdit'])->name('landing.team.edit');
        Route::put('/landing/team/{member}', [LandingContentController::class, 'teamUpdate'])->name('landing.team.update');
        Route::delete('/landing/team/{member}', [LandingContentController::class, 'teamDestroy'])->name('landing.team.destroy');

        Route::get('/landing/hki', [LandingContentController::class, 'hki'])->name('landing.hki');
        Route::get('/landing/hki/create', [LandingContentController::class, 'hkiCreate'])->name('landing.hki.create');
        Route::post('/landing/hki', [LandingContentController::class, 'hkiStore'])->name('landing.hki.store');
        Route::get('/landing/hki/{hki}/edit', [LandingContentController::class, 'hkiEdit'])->name('landing.hki.edit');
        Route::put('/landing/hki/{hki}', [LandingContentController::class, 'hkiUpdate'])->name('landing.hki.update');
        Route::delete('/landing/hki/{hki}', [LandingContentController::class, 'hkiDestroy'])->name('landing.hki.destroy');

        Route::get('/landing/partners', [LandingContentController::class, 'partners'])->name('landing.partners');
        Route::get('/landing/partners/create', [LandingContentController::class, 'partnersCreate'])->name('landing.partners.create');
        Route::post('/landing/partners', [LandingContentController::class, 'partnersStore'])->name('landing.partners.store');
        Route::get('/landing/partners/{partner}/edit', [LandingContentController::class, 'partnersEdit'])->name('landing.partners.edit');
        Route::put('/landing/partners/{partner}', [LandingContentController::class, 'partnersUpdate'])->name('landing.partners.update');
        Route::delete('/landing/partners/{partner}', [LandingContentController::class, 'partnersDestroy'])->name('landing.partners.destroy');

        // Organization users (superadmin only can create/manage)
        Route::get('/organizations', [\App\Http\Controllers\Superadmin\MemberController::class, 'organizations'])->name('organizations.index');
        Route::get('/organizations/create', [\App\Http\Controllers\Superadmin\MemberController::class, 'organizationCreate'])->name('organizations.create');
        Route::post('/organizations', [\App\Http\Controllers\Superadmin\MemberController::class, 'organizationStore'])->name('organizations.store');
        Route::get('/organizations/{member}/quota', [\App\Http\Controllers\Superadmin\MemberController::class, 'quotaEdit'])->name('organizations.quota');
        Route::put('/organizations/{member}/quota', [\App\Http\Controllers\Superadmin\MemberController::class, 'quotaUpdate'])->name('organizations.quota.update');
    });
});

// ============================================
// MEMBER ROUTES
// ============================================
Route::prefix('member')->name('member.')->middleware(['auth', 'role:member'])->group(function () {
    Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');

    // Children
    Route::resource('children', ChildController::class)->except(['show']);

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

