<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SatpamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FeedbackController;


// Landing Page Section
Route::get('/', function () {
    return view('landing.index');
});

Route::get('/landing-items', [ItemController::class, 'index'])->name('landing.items');

// Route untuk halaman login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');  // Halaman login
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');    // Proses login

// Route untuk logout
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Route untuk halaman registrasi
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');  // Halaman registrasi
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');  // Proses registrasi


// ---------------------------------------------------------------------------------------------------------------------------------------------------------

# ADMIN SECTION
// Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin_dashboard');
Route::get('/admin/lost', [AdminController::class, 'lost'])->name('admin_dashboard_lost'); // Items Lost
Route::get('/admin/found', [AdminController::class, 'found'])->name('admin_dashboard_found'); // Items Found
Route::get('/admin/user', [AdminController::class, 'user'])->name('admin_dashboard_user');
Route::get('/admin/approval', [AdminController::class, 'approval'])->name('admin_dashboard_approval');

# Halaman Functional Admin
Route::get('/items/filter', [ItemController::class, 'filterItems'])->name('filter.items');
Route::get('/dashboard/lost-items', [ItemController::class, 'showLostItems']);
Route::get('/dashboard/found-items', [ItemController::class, 'showFoundItems']);
Route::get('/dashboard/recent-items', [ItemController::class, 'showRecentItems']);
// Route::get('/admin/user/search', [AdminController::class, 'search'])->name('user.admin.users.search');

# ADMIN CRUD
// Route untuk edit user
Route::get('/admin/users/{user}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
Route::put('/admin/users/{user}', [AdminController::class, 'update'])->name('admin.users.update');
Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');

// Aprove Admin
Route::post('/admin/items/{id}/approve', [AdminController::class, 'approveItem'])->name('admin.approve.item');
Route::post('/admin/items/{id}/reject', [AdminController::class, 'rejectItem'])->name('admin.reject.item');

// Route untuk dashboard admin
Route::get('/admin/dashboard', [AdminController::class, 'index'])
    ->middleware(['auth', 'role:admin'])  // Pastikan role admin bisa akses
    ->name('admin_dashboard');



// --------------------------------------------------------------------------------------------------------------------------------------------------------

# SATPAM SECTION
Route::middleware(['auth', 'role:satpam'])->group(function () {
    Route::get('/satpam/dashboard', [SatpamController::class, 'index'])->name('satpam_dashboard');
    Route::get('/satpam/approval', [SatpamController::class, 'approval'])->name('satpam_dashboard_approval');
    Route::get('/satpam/lost', [SatpamController::class, 'lost'])->name('satpam_dashboard_lost');
    Route::get('/satpam/found', [SatpamController::class, 'found'])->name('satpam_dashboard_found');
    Route::get('/satpam/user', [SatpamController::class, 'user'])->name('satpam_dashboard_user');
});




// ---------------------------------------------------------------------------------------------------------------------------------------------------------


// USER SECTION
Route::get('dashboard', [ItemController::class, 'dashboard'])
    ->middleware(['auth', 'role:user'])  // Pastikan hanya user yang bisa akses
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/picture', [ProfileController::class, 'updatePicture'])->name('profile.picture.update');
    Route::delete('/profile/picture', [ProfileController::class, 'deletePicture'])->name('profile.picture.delete');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password.update');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/create-report', [ItemController::class, 'create'])->name('formReport');
    Route::post('/submit-report', [ItemController::class, 'store'])->name('report.store');
    Route::get('/activity', [ItemController::class, 'activity'])->name('activity');
});

Route::get('/activity', [ItemController::class, 'activity'])
    ->middleware('auth')
    ->name('activity');


// Functional User 
Route::resource('items', ItemController::class)->only([
    'update',
    'destroy'
])->middleware('auth');

Route::get('/about-us', [FeedbackController::class, 'showAboutUs'])->name('about-us');
Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');











# USER SECTION UN USED !!!!!
// Route::get('dashboard', [UserController::class, 'index'])->name('dashboard');

// Route untuk dashboard, hanya bisa diakses oleh pengguna yang sudah login
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware('auth')->name('dashboard');

// Route::get('/admin/dashboard', function () {
//     return view('admin.admin_dashboard');
// })->middleware('auth')->name('admin_dashboard');