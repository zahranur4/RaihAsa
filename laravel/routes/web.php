<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Static view routes (directly return Blade views)
Route::view('/home', 'home.index')->name('home');
Route::view('/login', 'login.index')->name('login');
Route::view('/register', 'register.index')->name('register');
Route::view('/volunteer', 'volunteer.index')->name('volunteer');
Route::view('/wishlist', 'wishlist.index')->name('wishlist');
Route::view('/food-rescue', 'food-rescue.index')->name('food-rescue');
Route::view('/my-donations', 'my-donations.index')->name('my-donations');
