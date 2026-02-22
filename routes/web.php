<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CmsPageController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VacancyController;
use App\Http\Controllers\CmsController;
use App\Http\Controllers\CVController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CandidateDashboardController;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

Route::get('/dashboard', [CandidateDashboardController::class, 'index'])->name('dashboard');

// Страница "Проверьте почту"
Route::get('/email/verify', function () {
    return view('auth.verify-email'); 
})->middleware('auth.candidate')->name('verification.notice');

// Ссылка подтверждения из письма
Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/')->with('success', 'Email verified!');
})->middleware(['auth.candidate', 'signed'])->name('verification.verify');



// Повторная отправка письма
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth.candidate', 'throttle:6,1'])->name('verification.send');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/dashboard/ai-match', [CandidateDashboardController::class, 'aiMatch'])
    ->name('dashboard.ai_match');
});

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('userlogin');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('userlogout');

// Группа для админки
Route::prefix('manage2026_albatros')->group(function () {
    
    // Страница входа (доступна всем)
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');

    // Защищенные маршруты (только для авторизованных админов)
    Route::middleware(['auth', 'admin'])->group(function () {
          Route::get('', [DashboardController::class, 'index'])->name('admin.dashboard');
          Route::post('cms-pages/upload-image', [CmsPageController::class, 'uploadImage'])->name('admin.cms.upload_image');
          Route::resource('cms-pages', CmsPageController::class)->names('admin.cms');
          Route::resource('users', UserController::class)->names('admin.users');		  
          Route::resource('vacancies', VacancyController::class)->names('admin.vacancies');
    });
});


// Public CMS Pages - should be at the bottom of the file
Route::get('/page/{slug}', [CmsController::class, 'show'])->name('pages.show');
// Страница с формой загрузки
Route::get('/upload-cv', [CVController::class, 'showUploadForm'])->name('cv.upload.form');

// Обработка загрузки (уже должен быть у тебя)
Route::post('/cv-upload', [CVController::class, 'upload'])->name('cv.upload');