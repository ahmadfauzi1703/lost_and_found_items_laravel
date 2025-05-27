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
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard Admin
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin_dashboard');

    // Halaman Admin lainnya
    Route::get('/admin/lost', [AdminController::class, 'lost'])->name('admin_dashboard_lost');
    Route::get('/admin/found', [AdminController::class, 'found'])->name('admin_dashboard_found');
    Route::get('/admin/user', [AdminController::class, 'user'])->name('admin_dashboard_user');
    Route::get('/admin/approval', [AdminController::class, 'approval'])->name('admin_dashboard_approval');

    // Admin CRUD
    Route::get('/admin/users/{user}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [AdminController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
    Route::delete('/admin/items/{item}', [AdminController::class, 'destroyItems'])->name('admin.items.destroy');


    // Approve/Reject Items
    Route::post('/admin/items/{id}/approve', [AdminController::class, 'approveItem'])->name('admin.approve.item');
    Route::post('/admin/items/{id}/reject', [AdminController::class, 'rejectItem'])->name('admin.reject.item');
});

// Halaman Functional Admin - juga dilindungi dengan middleware
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/items/filter', [ItemController::class, 'filterItems'])->name('filter.items');
    Route::get('/dashboard/lost-items', [ItemController::class, 'showLostItems']);
    Route::get('/dashboard/found-items', [ItemController::class, 'showFoundItems']);
    Route::get('/dashboard/recent-items', [ItemController::class, 'showRecentItems']);
});



// --------------------------------------------------------------------------------------------------------------------------------------------------------

# SATPAM SECTION
Route::middleware(['auth', 'role:satpam'])->group(function () {
    Route::get('/satpam/dashboard', [SatpamController::class, 'index'])->name('satpam_dashboard');
    // Route satpam dashboard view
    Route::get('/satpam/items', [App\Http\Controllers\SatpamController::class, 'viewItems'])
        ->middleware(['auth', 'role:satpam'])
        ->name('satpam.dashboard.view');

    // Functional Satpam
    Route::get('/satpam/item/create', [SatpamController::class, 'create'])->name('satpam.dashboard.create');
    Route::post('/satpam/item/store', [SatpamController::class, 'store'])->name('satpam.dashboard.store');

    Route::get('/items/{item}', [SatpamController::class, 'getItemDetails']);
    Route::put('/satpam/items/{item}', [App\Http\Controllers\SatpamController::class, 'updateItem'])
        ->middleware(['auth', 'role:satpam'])
        ->name('satpam.items.update');

    Route::get('/satpam/claims/create', [App\Http\Controllers\SatpamController::class, 'createClaim'])
        ->middleware(['auth', 'role:satpam'])
        ->name('satpam.dashboard.createClaim');

    Route::post('/satpam/claims', [App\Http\Controllers\SatpamController::class, 'storeClaim'])
        ->middleware(['auth', 'role:satpam'])
        ->name('satpam.claims.store');

    Route::get('/satpam/claims/history', [App\Http\Controllers\SatpamController::class, 'viewHistory'])
        ->middleware(['auth', 'role:satpam'])
        ->name('satpam.dashboard.viewHistory');

    Route::get('/satpam/profile', [App\Http\Controllers\SatpamController::class, 'profile'])
        ->middleware(['auth', 'role:satpam'])
        ->name('satpam.dashboard.profile');

    Route::post('/satpam/profile/update', [App\Http\Controllers\SatpamController::class, 'updateProfile'])
        ->middleware(['auth', 'role:satpam'])
        ->name('satpam.profile.update');
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

Route::get('/about-us', [App\Http\Controllers\UserController::class, 'aboutUs'])->name('about-us');


// Functional User 
Route::resource('items', ItemController::class)->only([
    'update',
    'destroy'
])->middleware('auth');

// Ganti dari ClaimController dan ReturnController ke ItemController
Route::get('/claim-item', [App\Http\Controllers\ItemController::class, 'showClaimForm'])
    ->middleware('auth')
    ->name('claim.form');

Route::post('/claim-item', [App\Http\Controllers\ItemController::class, 'processClaim'])
    ->middleware('auth')
    ->name('claim.submit');

Route::get('/return-item', [App\Http\Controllers\ItemController::class, 'showReturnForm'])
    ->middleware('auth')
    ->name('return.form');

Route::post('/return-item', [App\Http\Controllers\ItemController::class, 'processReturn'])
    ->middleware('auth')
    ->name('return.submit');

Route::post('/items/claim/update-status', [ItemController::class, 'updateClaimStatus'])->name('claim.update-status');

Route::get('/items/{item}/claim', [App\Http\Controllers\ItemController::class, 'showClaimForm'])
    ->name('items.claim.form')
    ->middleware('auth');

// Route untuk menyimpan data klaim
Route::post('/items/{item}/claim', [App\Http\Controllers\ItemController::class, 'storeClaim'])
    ->name('items.claim.store')
    ->middleware('auth');




// Nontification Functionality

// filepath: routes/web.php
// Tambahkan dalam grup middleware auth
Route::middleware(['auth'])->group(function () {
    // Routes lain yang sudah ada...

    // Notification routes
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index']);
    Route::put('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead']);
    Route::put('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/{id}', [App\Http\Controllers\NotificationController::class, 'destroy']);
});































# USER SECTION UN USED !!!!!
// Route::get('dashboard', [UserController::class, 'index'])->name('dashboard');

// Route untuk dashboard, hanya bisa diakses oleh pengguna yang sudah login
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware('auth')->name('dashboard');

// Route::get('/admin/dashboard', function () {
//     return view('admin.admin_dashboard');
// })->middleware('auth')->name('admin_dashboard');