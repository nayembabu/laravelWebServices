<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;


Route::get('/', function () {
    return view('welcome');
})->name('home');

// LOGIN
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// REGISTER
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// LOGOUT
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ADMIN
Route::middleware(['auth','admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/allOrders', [AdminController::class, 'all_waiting_orders'])->name('admin.order_waiting');
    Route::get('/allorder_see', [AdminController::class, 'all_waiting_order_see'])->name('admin.order_waiting_see');
    Route::get('/allorder_mybox_see', [AdminController::class, 'all_waiting_mybox_order_see'])->name('admin.mybox_order_waiting');
    Route::post('/user/assign-admin-order', [AdminController::class, 'assignAdminOrder'])->name('admin.assign_admin_order');
    Route::post('/user/submit-admin-order', [AdminController::class, 'submitAdminOrder'])->name('admin.submit_order');
    Route::post('/user/reject-admin-order', [AdminController::class, 'rejectAdminOrder'])->name('admin.reject_order');
    Route::get('/mybox-orders', [AdminController::class, 'mybox_orders'])->name('admin.mybox_order_waiting_get');
});

// USER
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/allservices', [UserController::class, 'view_all_services'])->name('user.services');
    Route::post('/addservices', [UserController::class, 'order_services'])->name('user.addservice');
    Route::get('/downloads', [UserController::class, 'download_order_file'])->name('user.downloads');
    Route::get('/user/orders/ajax', [UserController::class, 'ordersAjax'])->name('user.orders.ajax');
    Route::get('deposite', [UserController::class, 'deposite_user_accounts'])->name('user.deposite');
    Route::post('deposite', [UserController::class, 'deposite_request'])->name('user.deposite_req');
    Route::get('profile', [UserController::class, 'view_user_profile'])->name('user.profile');
    Route::post('profile', [UserController::class, 'update_user_profile'])->name('user.profileupdate');

});

Route::get('/permission-denied', function () {
    return view('errors.permission');
})->name('permission.denied');
