<?php

use Illuminate\Support\Facades\Route;


// Static view routes (directly return Blade views)
Route::view('/', 'home.index')->name('home');
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
Route::post('/register/donor', [RegisterController::class, 'registerDonor'])->name('register.donor');
Route::post('/register/recipient', [RegisterController::class, 'registerRecipient'])->name('register.recipient');
Route::view('/volunteer', 'volunteer.index')->name('volunteer');
Route::view('/wishlist', 'wishlist.index')->name('wishlist');
Route::view('/food-rescue', 'food-rescue.index')->name('food-rescue');
Route::view('/my-donations', 'my-donations.index')->name('my-donations');

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;

Route::prefix('admin')->name('admin.')->middleware(['auth', \App\Http\Middleware\EnsureAdmin::class])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Manajemen pengguna
    Route::get('/manajemen-pengguna', [UserController::class, 'index'])->name('users.index');
    Route::get('/manajemen-pengguna/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/manajemen-pengguna/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/manajemen-pengguna/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});
