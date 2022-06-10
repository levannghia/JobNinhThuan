<?php

use App\Http\Controllers\Dashboard\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\PermissionController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\ThongTinTuyenDungController;
use App\Http\Controllers\Dashboard\WorkLocationController;
use App\Http\Controllers\Site\HomeSiteController;
use App\Http\Controllers\Site\SeekerSiteController;
use App\Http\Controllers\Site\EmployerSiteController;
use App\Http\Controllers\Site\RecruitmentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//dashboard
Route::prefix('dashboard')->name('dashboard.')->group(function () {

    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('post-login', [AuthController::class, 'postLogin'])->name('post.login');
    Route::get('register', [AuthController::class, 'register'])->name('register');
    Route::get('confirm-acount', [AuthController::class, 'confirmAccount'])->name('confirm.acount');
    Route::post('register', [AuthController::class, 'postRegister'])->name('post.register');


    Route::middleware(['check.admin'])->group(function () {
        Route::get('/',[DashboardController::class,'index'])->name('index');
        Route::get('logout', [AuthController::class, 'logout'])->name('logout');
        //users
        Route::resource('user', UserController::class);

        //Thông tin tuyển dụng
        Route::resource('thong-tin-tuyen-dung', ThongTinTuyenDungController::class);

        //Địa điểm làm việc
        Route::resource('dia-diem-lam-viec',WorkLocationController::class);

        //Vai trò
        Route::resource('vai-tro',RoleController::class);

        //permission
        Route::resource('permission', PermissionController::class);

    });
});

Route::middleware(['web'])->group(function(){
    Route::get('/',[HomeSiteController::class,'index'])->name('home');

    Route::prefix('seeker')->name('seeker.')->group(function(){
        Route::get('login',[SeekerSiteController::class,'login'])->name('login');
        Route::get('register',[SeekerSiteController::class,'register'])->name('register');
        Route::get('login-facebook',[SeekerSiteController::class,'loginFacebook'])->name('login.facebook');
        Route::get('facebook-callback',[SeekerSiteController::class,'callbackFacebook'])->name('callback.facebook');
        Route::get('login-google',[SeekerSiteController::class,'loginGoogle'])->name('login.google');
        Route::get('google-callback',[SeekerSiteController::class,'callbackGoogle'])->name('callback.google');
        Route::post('post-login', [SeekerSiteController::class, 'postLogin'])->name('post.login');
        Route::post('post-register',[SeekerSiteController::class,'postRegister'])->name('post.register');
        Route::get('confirm-acount', [SeekerSiteController::class, 'confirmAccount'])->name('confirm.acount');
        Route::get('logout', [SeekerSiteController::class, 'logout'])->name('logout');

        Route::prefix('profiles')->name('profile.')->middleware(['check.seeker'])->group(function(){
            Route::get('/',[SeekerSiteController::class,'manageProfile'])->name('index.profile');
            Route::get('create',[SeekerSiteController::class,'createProfile'])->name('create.profile');
            Route::post('store',[SeekerSiteController::class,'storeProfile'])->name('store.profile');
            Route::get('edit/{slug}/{id}',[SeekerSiteController::class,'editProfile'])->name('edit.profile');
            Route::post('update/{id}',[SeekerSiteController::class,'updateProfile'])->name('update.profile');
            Route::get('update-status',[SeekerSiteController::class,'updateStatus'])->name('update.status');
            Route::get('delete',[SeekerSiteController::class,'deleteProfile'])->name('delete.profile');     
        });
    });

    Route::prefix('employer')->name('employer.')->group(function(){
        Route::get('login',[EmployerSiteController::class,'login'])->name('login');
        Route::get('register',[EmployerSiteController::class,'register'])->name('register');
        Route::post('post-login', [EmployerSiteController::class, 'postLogin'])->name('post.login');
        Route::post('post-register',[EmployerSiteController::class,'postRegister'])->name('post.register');
        Route::get('confirm-acount', [EmployerSiteController::class, 'confirmAccount'])->name('confirm.acount');
        Route::get('logout', [EmployerSiteController::class, 'logout'])->name('logout');

        Route::prefix('jobs')->name('job.')->middleware(['check.employer'])->group(function(){
            Route::get('/',[EmployerSiteController::class,'manageJob'])->name('index');
            Route::get('create',[EmployerSiteController::class,'createJob'])->name('create');
            Route::get('profile',[EmployerSiteController::class,'profileEmployer'])->name('profile');
            Route::post('update-profile/{id}',[EmployerSiteController::class,'updateProfileEmployer'])->name('update.profile');
            Route::post('store',[EmployerSiteController::class,'storeJob'])->name('store');
            Route::get('edit/{slug}/{id}',[EmployerSiteController::class,'editJob'])->name('edit');
            Route::post('update/{id}',[EmployerSiteController::class,'updateJob'])->name('update');
            Route::get('update-status',[EmployerSiteController::class,'updateStatus'])->name('update.status');
            Route::get('delete',[EmployerSiteController::class,'deleteJob'])->name('delete');     
        });
    });

    Route::prefix('tin-tuyen-dung')->name('recruitment.')->group(function(){
        Route::get('/',[RecruitmentController::class,'index'])->name('index');
        Route::get('/{slug}/{id}',[RecruitmentController::class,'jobDetail'])->name('job.detail');
        Route::get('/search-information',[RecruitmentController::class,'searchInformation'])->name('search.information');
        Route::get('wishlist',[RecruitmentController::class,'wishList'])->name('wishlist');
    });
});