<?php

use App\Http\Controllers\Admin\CareerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CertificationController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\ProjectPublicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Frontend Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/projects', [ProjectPublicController::class, 'index'])->name('projects.index');
Route::get('/projects/{slug}', [ProjectPublicController::class, 'show'])->name('projects.show');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/resume', [ResumeController::class, 'show'])->name('resume.show');

/*
|--------------------------------------------------------------------------
| Admin Back-Office Routes (TailAdmin & Yajra DataTables)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', fn() => redirect()->route('admin.dashboard'))
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Projects CRUD
    Route::resource('projects', ProjectController::class)->except(['show']);

    // Clients CRUD
    // Add/edit happen in a modal on the index page.
    Route::resource('clients', ClientController::class)->only(['index', 'store', 'update', 'destroy']);

    // Blog Posts CRUD
    Route::resource('posts', PostController::class)->except(['show']);

    // Categories CRUD (Blog & Project Categories)
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Career Timeline CRUD
    // Add/edit happen in a modal on the index page, so there are no create/edit pages.
    // reorder must be registered before the resource or PATCH careers/{career} would catch it.
    Route::patch('careers/reorder', [CareerController::class, 'reorder'])->name('careers.reorder');
    Route::resource('careers', CareerController::class)->only(['index', 'store', 'update', 'destroy']);

    // Certifications & Credentials CRUD
    Route::resource('certifications', CertificationController::class)->except(['show']);

    // Inquiries Management
    Route::get('inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
    Route::get('inquiries/export', [InquiryController::class, 'export'])->name('inquiries.export');
    Route::get('inquiries/{inquiry}', [InquiryController::class, 'show'])->name('inquiries.show');
    Route::patch('inquiries/{inquiry}/status', [InquiryController::class, 'updateStatus'])->name('inquiries.update-status');
    Route::delete('inquiries/{inquiry}', [InquiryController::class, 'destroy'])->name('inquiries.destroy');

    // Site Branding & SEO Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
