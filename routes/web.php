<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\BkashPaymentController;


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
    Route::post('/settings/service-toggle', [AdminController::class,'toggleServiceOrder'])->name('admin.setting.toggle.service');
    Route::get('/deposit', [AdminController::class, 'deposit_list'])->name('admin.deposit');
    Route::post('/deposit/update-status', [AdminController::class, 'update_deposit_status'])->name('admin.deposit.update_status');
    Route::get('/users', [AdminController::class, 'user_list'])->name('admin.user_list');
    Route::get('/users/list', [AdminController::class,'list'])->name('admin.users.list');
    Route::get('/users/{id}', [AdminController::class,'show'])->name('admin.users.show');
    Route::post('/users/add-balance', [AdminController::class,'addBalance'])->name('admin.users.update_balance_add');
    Route::post('/users/deduct-balance', [AdminController::class,'deductBalance'])->name('admin.users.update_balance_cut');
    Route::post('/users/update-password', [AdminController::class,'updatePassword'])->name('admin.users.update_password');
    Route::get('/deposit/pending', [AdminController::class,'pendingDeposits'])->name('admin.deposit.pending');
    Route::get('/serviceslist', [AdminController::class,'adminServicesList'])->name('admin.services.list');
    Route::post('/services/toggle-status', [AdminController::class,'serviceToggleStatus'])->name('admin.services.toggleStatus');
    Route::get('/services/list', [AdminController::class,'servicesList'])->name('admin.services.lists');
    Route::post('/services/update-rate', [AdminController::class,'serviceUpdateRate'])->name('admin.services.updateRate');
    Route::post('/services/store', [AdminController::class,'serviceStore'])->name('admin.services.store');
    Route::get('/today-orders', [AdminController::class,'todayOrders'])->name('admin.today_orders');
    Route::get('/today-orders-list', [AdminController::class,'todayOrdersList'])->name('admin.today_orders_list');

});

// USER
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/allservices', [UserController::class, 'view_all_services'])->name('user.services');
    Route::post('/addservices', [UserController::class, 'order_services'])->name('user.addservice');
    Route::get('/downloads', [UserController::class, 'download_order_file'])->name('user.downloads');
    Route::get('/download_make', [UserController::class, 'download_make_file'])->name('user.download_make');
    Route::get('/user/orders/ajax', [UserController::class, 'ordersAjax'])->name('user.orders.ajax');
    Route::get('deposite', [UserController::class, 'deposite_user_accounts'])->name('user.deposite');
    Route::post('deposite', [UserController::class, 'deposite_request'])->name('user.deposite_req');
    Route::get('profile', [UserController::class, 'view_user_profile'])->name('user.profile');
    Route::post('profile', [UserController::class, 'update_user_profile'])->name('user.profileupdate');

    Route::get('sign_nid', [UserController::class, 'signtonid'])->name('user.sign_nid');
    Route::post('sign2nid', [UserController::class, 'signtonid_api_fetch'])->name('user.ni2sign');
    Route::post('nid_data_save_download', [UserController::class, 'nid_data_save_and_download'])->name('user.nidSaveData');

    Route::get('auto_server_data_get', [UserController::class, 'auto_server_copy_data_get'])->name('user.getserverdata');
    Route::get('auto_server', [UserController::class, 'auto_server_copy'])->name('user.server');
    Route::post('auto_serv', [UserController::class, 'auto_server_copy_api'])->name('user.serverap');



    Route::get('don', [DownloadController::class, 'generatePdf'])->name('user.sign_nid');
    Route::get('pdf-download', [DownloadController::class, 'download']);




    Route::get('downloadnid/{voter_id}', [DownloadController::class, 'download_nid_copy'])->name('user.downloadsnid');
    Route::get('downloadserver', [DownloadController::class, 'serverAction'])->name('voter.action');






    Route::get('/pay', [BkashPaymentController::class, 'showForm'])->name('pay.form');
    Route::post('/pay', [BkashPaymentController::class, 'startPayment'])->name('pay.start');

    Route::get('/bkash/callback', [BkashPaymentController::class, 'callback'])->name('bkash.callback');

    Route::get('/payment/success/{invoice}', [BkashPaymentController::class, 'success'])->name('pay.success');
    Route::get('/payment/failed/{invoice}', [BkashPaymentController::class, 'failed'])->name('pay.failed');

    Route::get('/servercopy/token', [UserController::class, 'servercopyToken'])->name('servercopy.token');

});

Route::get('/permission-denied', function () { return view('errors.permission'); })->name('permission.denied');
