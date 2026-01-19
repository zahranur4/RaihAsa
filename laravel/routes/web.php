<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\VolunteerRegistrationController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\FoodRescueController;
use App\Http\Controllers\MyDonationsController;
use App\Http\Controllers\Donor;
use App\Http\Controllers\Panti;

// Home page
Route::view('/', 'home.index')->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
Route::post('/register/donor', [RegisterController::class, 'registerDonor'])->name('register.donor');
Route::post('/register/recipient', [RegisterController::class, 'registerRecipient'])->name('register.recipient');
Route::get('/volunteer', [VolunteerController::class, 'index'])->name('volunteer');
Route::get('/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist');
Route::get('/wishlist/matching', [\App\Http\Controllers\DonationMatchingController::class, 'findMatches'])->name('wishlist.matching');
Route::post('/wishlist/{id}/fulfill', [\App\Http\Controllers\DonationMatchingController::class, 'fulfillWishlist'])->name('wishlist.fulfill')->middleware('auth');
Route::get('/wishlist/pledge/{id}', [\App\Http\Controllers\DonationMatchingController::class, 'showPledge'])->name('wishlist.pledge-detail')->middleware('auth');
Route::post('/wishlist/pledge/{id}/confirm', [\App\Http\Controllers\DonationMatchingController::class, 'confirmPledge'])->name('wishlist.pledge.confirm')->middleware('auth');
Route::get('/food-rescue', [FoodRescueController::class, 'index'])->name('food-rescue');
Route::post('/food-rescue/{id}/claim', [FoodRescueController::class, 'claim'])->name('food-rescue.claim')->middleware('auth');
Route::get('/food-rescue/{id}', [FoodRescueController::class, 'detail'])->name('food-rescue.detail');
Route::get('/my-donations', [MyDonationsController::class, 'index'])->name('my-donations');
// Register-panti page removed; redirect to the main register selection
Route::redirect('/register-panti', '/register');

// Volunteer registration routes
Route::get('/volunteer/register', [VolunteerRegistrationController::class, 'create'])->middleware('auth')->name('volunteer.register');
Route::post('/volunteer/register', [VolunteerRegistrationController::class, 'store'])->middleware('auth')->name('volunteer.register.store');
Route::get('/volunteer/status', [VolunteerRegistrationController::class, 'status'])->middleware('auth')->name('volunteer.status');
Route::get('/volunteer/dashboard', [VolunteerController::class, 'dashboard'])->middleware('auth')->name('volunteer.dashboard');

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DonasiController;
use App\Http\Controllers\Admin\PantiProfileController;
use App\Http\Controllers\Admin\RelawanProfileController;
use App\Http\Controllers\Panti\ProfileController;
use App\Http\Controllers\Panti\WishlistController;
use App\Http\Controllers\Panti\DonasiMasukController;

Route::prefix('admin')->name('admin.')->middleware(['auth', \App\Http\Middleware\EnsureAdmin::class])->group(function () {
    // Redirect /admin to dashboard
    Route::get('/', function () { return redirect()->route('admin.dashboard'); })->name('index');

    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Static admin pages (views)
    // Manajemen Donasi (CRUD)
    Route::get('/manajemen-donasi', [DonasiController::class, 'index'])->name('donations.index');
    Route::get('/manajemen-donasi/create', [DonasiController::class, 'create'])->name('donations.create');
    Route::post('/manajemen-donasi', [DonasiController::class, 'store'])->name('donations.store');
    Route::get('/manajemen-donasi/{id}/edit', [DonasiController::class, 'edit'])->name('donations.edit');
    Route::put('/manajemen-donasi/{id}', [DonasiController::class, 'update'])->name('donations.update');
    Route::delete('/manajemen-donasi/{id}', [DonasiController::class, 'destroy'])->name('donations.destroy');
    // Food Rescue (CRUD)
    Route::get('/food-rescue', [FoodRescueController::class, 'adminIndex'])->name('food-rescue.index');
    Route::get('/food-rescue/create', [FoodRescueController::class, 'create'])->name('food-rescue.create');
    Route::post('/food-rescue', [FoodRescueController::class, 'store'])->name('food-rescue.store');
    Route::get('/food-rescue/{id}/edit', [FoodRescueController::class, 'edit'])->name('food-rescue.edit');
    Route::put('/food-rescue/{id}', [FoodRescueController::class, 'update'])->name('food-rescue.update');
    Route::delete('/food-rescue/{id}', [FoodRescueController::class, 'destroy'])->name('food-rescue.destroy');
    // Manajemen Penerima (Panti Profiles)
    Route::get('/manajemen-penerima', [PantiProfileController::class, 'index'])->name('recipients.index');
    Route::get('/manajemen-penerima/create', [PantiProfileController::class, 'create'])->name('recipients.create');
    Route::post('/manajemen-penerima', [PantiProfileController::class, 'store'])->name('recipients.store');
    Route::get('/manajemen-penerima/{id}/edit', [PantiProfileController::class, 'edit'])->name('recipients.edit');
    Route::put('/manajemen-penerima/{id}', [PantiProfileController::class, 'update'])->name('recipients.update');
    Route::delete('/manajemen-penerima/{id}', [PantiProfileController::class, 'destroy'])->name('recipients.destroy');
    // Manajemen Relawan (CRUD)
    Route::get('/manajemen-relawan', [RelawanProfileController::class, 'index'])->name('volunteers.index');
    Route::get('/manajemen-relawan/create', [RelawanProfileController::class, 'create'])->name('volunteers.create');
    Route::post('/manajemen-relawan', [RelawanProfileController::class, 'store'])->name('volunteers.store');
    Route::get('/manajemen-relawan/{id}/edit', [RelawanProfileController::class, 'edit'])->name('volunteers.edit');
    Route::put('/manajemen-relawan/{id}', [RelawanProfileController::class, 'update'])->name('volunteers.update');
    Route::delete('/manajemen-relawan/{id}', [RelawanProfileController::class, 'destroy'])->name('volunteers.destroy');
    Route::view('/pengaturan', 'admin.pengaturan-admin.index')->name('settings.index');
    Route::view('/laporan', 'admin.laporan-admin.index')->name('reports.index');

    // Manajemen pengguna
    Route::get('/manajemen-pengguna', [UserController::class, 'index'])->name('users.index');
    Route::post('/manajemen-pengguna', [UserController::class, 'store'])->name('users.store');
    Route::get('/manajemen-pengguna/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/manajemen-pengguna/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/manajemen-pengguna/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});

// Panti (recipient) pages
Route::prefix('panti')->name('panti.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () { return view('panti.dashboard.index'); })->name('dashboard');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::put('/wishlist/{id}', [WishlistController::class, 'update'])->name('wishlist.update');
    Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
    Route::get('/donasi-masuk', [DonasiMasukController::class, 'index'])->name('donasi-masuk');
    Route::post('/donasi-masuk/{id}/confirm', [DonasiMasukController::class, 'confirmReceipt'])->name('donasi-masuk.confirm');
    Route::get('/donasi-masuk/{id}/detail', [DonasiMasukController::class, 'viewDetail'])->name('donasi-masuk.detail');
    Route::get('/food-rescue', function () { return view('panti.food-rescue.index'); })->name('food-rescue');
    Route::get('/laporan', function () { return view('panti.laporan.index'); })->name('laporan');
    Route::get('/profil', [ProfileController::class, 'index'])->name('profil');
    Route::post('/profil', [ProfileController::class, 'update'])->name('profil.update');
    Route::get('/pengaturan', function () { return view('panti.pengaturan.index'); })->name('pengaturan');
});

// Donor profile page
Route::get('/donor-profile', [Donor\ProfileController::class, 'index'])->name('donor-profile')->middleware('auth');
Route::post('/donor-profile', [Donor\ProfileController::class, 'update'])->name('donor-profile.update')->middleware('auth');
Route::post('/donor-profile/password', [Donor\ProfileController::class, 'updatePassword'])->name('donor-profile.update-password')->middleware('auth');

