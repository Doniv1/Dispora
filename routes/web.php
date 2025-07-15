<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\UserRoleAccess;
use App\Http\Middleware\DashboardRoleAccess;

use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TableManagement;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterController;
use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;

// AUTH PROSES
Route::get('/', function () {
  return redirect('/home');
});


// Halaman login hanya bisa diakses kalau BELUM login
Route::middleware(UserRoleAccess::class)->group(function () {
  // GET METHOD
  Route::get('/home', [UserController::class, 'index'])->name('home');
  Route::get('/training', [UserController::class, 'training'])->name('training');
  Route::get('/mytraining', [UserController::class, 'mytraining'])->name('mytraining');

  // POST METHOD
  Route::post('contact/insert', [UserController::class, 'insert_contact'])->name('contact.insert');
  Route::post('subscribe/insert', [UserController::class, 'insert_subscribe'])->name('subscribe.insert');
  Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
  Route::post('/register1', [AuthController::class, 'register1'])->name('auth.register.first');
  Route::post('/register/training', [UserController::class, 'register_training'])->name('register.training');
  Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
  Route::post('/get_detail_training', [UserController::class, 'get_detail_training'])->name('detail.training');
});

// Halaman dashboard hanya bisa diakses kalau SUDAH login
Route::middleware(DashboardRoleAccess::class)->group(function () {
  // GET METHOD (VIEW)

  // DASHBOARD CONTROLLER
  Route::controller(DashboardController::class)->group(function () {
    // DASHBOARD
    Route::get('/dashboard', 'index')->name('dashboard');
    // PROFILE
    Route::get('/profile', 'profile')->name('admin.profile');
    // CONTACT
    Route::get('/contact', 'contact')->name('contact');
    // APPROVAL
    Route::get('/approval', 'approval')->name('approval');
    Route::post('/set_approval', 'set_approval')->name('set.approval');
  });
  // SETTING CONTROLLER
  Route::get('/setting', [SettingController::class, 'index'])->name('setting');
  // MASTER CONTROLLER
  Route::controller(MasterController::class)->group(function () {
    Route::get('/master/user', 'user')->name('master.user');
    Route::get('/master/admin', 'admin')->name('master.admin');
    Route::get('/master/category', 'category')->name('master.category');
    Route::get('/master/vector', 'vector')->name('master.vector');
    Route::get('/master/banner', 'banner')->name('master.banner');
    Route::get('/master/training', 'training')->name('master.training');
  });





  //  POST METHOD

  // SETTING
  Route::post('/setting/logo', [SettingController::class, 'updateLogo'])->name('setting.logo');
  Route::post('/setting/seo', [SettingController::class, 'updateSeo'])->name('setting.seo');
  Route::post('/setting/sosmed', [SettingController::class, 'setupSosmed'])->name('setting.sosmed');
  Route::post('/setting/insert/sosmed', [SettingController::class, 'insert_sosmed'])->name('insert.sosmed');
  Route::post('/setting/update/about', [SettingController::class, 'updateAbout'])->name('update.about');
  Route::post('/setting/update/sosmed', [SettingController::class, 'update_sosmed'])->name('update.sosmed');
  Route::post('/setting/insert/form', [SettingController::class, 'insert_form'])->name('insert.form');
  Route::post('/setting/update/form', [SettingController::class, 'update_form'])->name('update.form');

  // FORM
  Route::post('/setting/update/form', [SettingController::class, 'update_form'])->name('update.form');
  Route::post('/setting/insert/form', [SettingController::class, 'insert_form'])->name('insert.form');
  Route::post('/modal_detail_register', [SettingController::class, 'modal_detail_register'])->name('modal.detail.register');


  // DASHBOARD
    Route::controller(DashboardController::class)->group(function () {
    // PROFILE
  });

  // DATATABLE
  Route::controller(TableManagement::class)->group(function () {
    // MASTER
    Route::post('/table/user', 'table_user')->name('table.user');
    Route::post('/table/admin', 'table_admin')->name('table.admin');
    // CONTACT
    Route::post('/table/contact', 'table_contact')->name('table.contact');
    // TRAINING
    Route::post('/table/training', 'table_training')->name('table.training');
    Route::post('/table/category', 'table_category')->name('table.category');
    Route::post('/table/vector', 'table_vector')->name('table.vector');
    Route::post('/table/form', 'table_form')->name('table.form');
    Route::post('/table/approval', 'table_approval')->name('table.approval');
    // BANNER
    Route::post('/table/banner', 'table_banner')->name('table.banner');


    // ORDER
    Route::post('/table/form/order', 'table_order_form')->name('table.form.order');
  });
  // MASTER CONTROLLER
  Route::controller(MasterController::class)->group(function () {
    // USER
    Route::post('/master/user/update', 'update_user')->name('update.user');
    Route::post('/master/user/insert', 'insert_user')->name('insert.user');
    // ADMIN
    Route::post('/master/admin/update', 'update_user')->name('update.admin');
    Route::post('/master/admin/insert', 'insert_user')->name('insert.admin');
    // CATEGORY
    Route::post('/master/category/update', 'update_category')->name('update.category');
    Route::post('/master/category/insert', 'insert_category')->name('insert.category');
    // VECTOR
    Route::post('/master/vector/update', 'update_vector')->name('update.vector');
    Route::post('/master/vector/insert', 'insert_vector')->name('insert.vector');
    // BANNER
    Route::post('/master/banner/update', 'update_banner')->name('update.banner');
    Route::post('/master/banner/insert', 'insert_banner')->name('insert.banner');
    // TRAINING
    Route::post('/master/training/update', 'update_training')->name('update.training');
    Route::post('/master/training/insert', 'insert_training')->name('insert.training');
    Route::post('/single_training', 'single_training')->name('single.training');
    Route::post('/modal_register', 'modal_register')->name('modal.register');
    Route::get('/download_register', 'download_register')->name('download.register');
    Route::post('/delete_regis', 'delete_regis')->name('delete.regis');
  });

  // AJAX

  // DATATABLE

  // GLOBAL FUNCTION
  Route::post('/switch/{db?}', [SettingController::class, 'switch']);
  Route::post('/delete', [SettingController::class, 'hapusdata']);
  Route::post('/single/{db?}/{id?}', [SettingController::class, 'single']);
});



Route::get('/report/export', function () {
  return Excel::download(new ReportExport, 'Data-pelaporan-karyawan.xlsx');
})->name('report.export');


Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
