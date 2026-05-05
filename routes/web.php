<?php

use Illuminate\Support\Facades\Route;

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

use App\Http\Controllers\SiteController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [SiteController::class, 'login'])->name('login');
    Route::post('/login', [SiteController::class, 'authenticate'])->name('authenticate');
    Route::get('/register', [SiteController::class, 'register'])->name('register');
    Route::post('/register', [SiteController::class, 'registerUser'])->name('register.user');
});

Route::middleware('auth')->group(function () {
    Route::get('/landing', [SiteController::class, 'landing'])->name('landing');
    Route::post('/logout', [SiteController::class, 'logout'])->name('logout');
    Route::get('/home', [SiteController::class, 'home'])->name('home');
    Route::get('/services', [SiteController::class, 'services'])->name('services');
    Route::get('/support', [SiteController::class, 'support'])->name('support');
    Route::get('/orders', [SiteController::class, 'orders'])->name('orders');
    Route::get('/schedule', [SiteController::class, 'schedule'])->name('schedule');
    Route::get('/schedule/{appointment}/edit', [SiteController::class, 'editSchedule'])->name('schedule.edit');
    Route::post('/schedule/confirm', [SiteController::class, 'scheduleConfirm'])->name('schedule.confirm');
    Route::post('/schedule/done', [SiteController::class, 'scheduleDone'])->name('schedule.done');
    Route::post('/appointments/{appointment}/cancel', [SiteController::class, 'cancelAppointment'])->name('appointments.cancel');
    Route::get('/about', [SiteController::class, 'about'])->name('about');
    Route::get('/contact', [SiteController::class, 'contact'])->name('contact');
});
