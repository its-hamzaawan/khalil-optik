<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public website
|--------------------------------------------------------------------------
*/
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/services', [PageController::class, 'services'])->name('services');

Route::get('/eyewear', [ProductController::class, 'index'])->name('products.index');
Route::get('/eyewear/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/appointment', [AppointmentController::class, 'create'])->name('appointment.create');
Route::get('/appointment/slots', [AppointmentController::class, 'slots'])->middleware('throttle:60,1')->name('appointment.slots');
Route::post('/appointment', [AppointmentController::class, 'store'])->middleware('throttle:5,1')->name('appointment.store');

Route::get('/contact', [ContactController::class, 'create'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');

/*
|--------------------------------------------------------------------------
| Admin panel  (/admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [Admin\AuthController::class, 'create'])->name('login');
        Route::post('login', [Admin\AuthController::class, 'store'])->middleware('throttle:6,1')->name('login.store');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [Admin\AuthController::class, 'destroy'])->name('logout');
        Route::get('/', Admin\DashboardController::class)->name('dashboard');

        Route::resource('products', Admin\ProductController::class)->except('show');

        Route::get('appointments', [Admin\AppointmentController::class, 'index'])->name('appointments.index');
        Route::patch('appointments/{appointment}', [Admin\AppointmentController::class, 'update'])->name('appointments.update');
        Route::delete('appointments/{appointment}', [Admin\AppointmentController::class, 'destroy'])->name('appointments.destroy');

        Route::get('messages', [Admin\MessageController::class, 'index'])->name('messages.index');
        Route::patch('messages/{message}/read', [Admin\MessageController::class, 'toggleRead'])->name('messages.read');
        Route::delete('messages/{message}', [Admin\MessageController::class, 'destroy'])->name('messages.destroy');
    });
});
