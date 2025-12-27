<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Static view routes (directly return Blade views)
Route::view('/home', 'home.index')->name('home');
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
