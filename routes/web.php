<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CmsPageController;
use App\Http\Controllers\CmsController;

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



// Группа для админки
Route::prefix('manage2026_albatros')->group(function () {
    
    // Страница входа (доступна всем)
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');

    // Защищенные маршруты (только для авторизованных админов)
    Route::middleware(['auth'])->group(function () {
          Route::get('', [DashboardController::class, 'index'])->name('admin.dashboard');
          Route::resource('cms-pages', CmsPageController::class)->names('admin.cms');
    });
});


// Public CMS Pages - should be at the bottom of the file
Route::get('/page/{slug}', [CmsController::class, 'show'])->name('pages.show');