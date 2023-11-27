<?php

use App\Http\Controllers\Dashboard\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\PermissionController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\CategoryAdminController;
use App\Http\Controllers\Dashboard\RecruitmentAdminController;
use App\Http\Controllers\Dashboard\FunctionController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\InformationAdminController;
use App\Http\Controllers\Dashboard\WorkLocationController;
use App\Http\Controllers\Site\CartSiteController;
use App\Http\Controllers\Site\CheckoutController;
use App\Http\Controllers\Site\CouponsController;
use App\Http\Controllers\Site\TelegramController;
use App\Http\Controllers\Site\HomeSiteController;
use App\Http\Controllers\Site\SeekerSiteController;
use App\Http\Controllers\Site\HoSoSiteController;
use App\Http\Controllers\Site\EmployerSiteController;
use App\Http\Controllers\Site\RecruitmentController;
use App\Http\Controllers\Site\ServiceSiteController;
use App\Http\Controllers\Site\PushController;
use App\Http\Controllers\site\WheelController;
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


// Route::get('resize-image/{pathkey}/{filename}/{w?}/{h?}', function($pathkey, $filename, $w=100, $h=100){
//     //die(public_path('images'));

// //        $filename ='/var/www/laravel/backpack-demo/storage/app/public/app/upload/08034af8455e92f91c738edc280163b1.jpg';
//     $cacheimage = Image::cache(function($image) use($pathkey, $filename, $w, $h){

//         switch($pathkey){
//             case 'tour-images':
//                 $filepath = 'upload/tour-images/' . $filename;
//                 break;
//         }
//         $filepath = public_path('upload').'/' . $filename;
//         return $image->make($filepath)->resize($w,$h);

//     },10,true); // cache for 10 minutes
    
//     return Response::make($cacheimage, 200, array('Content-Type' => 'image/jpeg'));
// });


Route::middleware(['web'])->group(function () {
    Route::get('vong-quay', [WheelController::class, 'index']);
    Route::post('subscriptions', [PushController::class, 'store']);
    Route::get('push', [PushController::class, 'push']);
    Route::get('/', [HomeSiteController::class, 'index'])->name('home');
    Route::get('/ok', [TelegramController::class, 'updatedActivity']);
    Route::get('/send', [TelegramController::class, 'sendMessage']);
    Route::prefix('seeker')->name('seeker.')->group(function () {
        Route::get('/login', [SeekerSiteController::class, 'login'])->name('login');
        Route::get('/register', [SeekerSiteController::class, 'register'])->name('register');
        Route::get('/login-facebook', [SeekerSiteController::class, 'loginFacebook'])->name('login.facebook');
        Route::get('/facebook-callback', [SeekerSiteController::class, 'callbackFacebook'])->name('callback.facebook');
        Route::get('/login-google', [SeekerSiteController::class, 'loginGoogle'])->name('login.google');
        Route::get('/google-callback', [SeekerSiteController::class, 'callbackGoogle'])->name('callback.google');
        Route::post('/post-login', [SeekerSiteController::class, 'postLogin'])->name('post.login');
        Route::post('/post-register', [SeekerSiteController::class, 'postRegister'])->name('post.register');
        Route::get('/confirm-acount', [SeekerSiteController::class, 'confirmAccount'])->name('confirm.acount');
        Route::get('/logout', [SeekerSiteController::class, 'logout'])->name('logout');

        Route::prefix('profiles')->name('profile.')->middleware(['check.seeker'])->group(function () {
            Route::get('/', [SeekerSiteController::class, 'manageProfile'])->name('index.profile');
            Route::get('/user', [SeekerSiteController::class, 'getProfileUser'])->name('index.profile.user');
            Route::post('/update-user/{id}', [SeekerSiteController::class, 'updateProfileUser'])->name('update.profile.user');
            Route::get('/viec-lam-da-luu', [SeekerSiteController::class, 'manageWishList'])->name('wishlist');
            Route::get('/viec-lam-da-ung-tuyen', [SeekerSiteController::class, 'manageApply'])->name('apply');
            Route::get('/create', [SeekerSiteController::class, 'createProfile'])->name('create.profile');
            Route::post('/store', [SeekerSiteController::class, 'storeProfile'])->name('store.profile');
            Route::get('/edit/{slug}/{id}', [SeekerSiteController::class, 'editProfile'])->name('edit.profile');
            Route::post('/update/{id}', [SeekerSiteController::class, 'updateProfile'])->name('update.profile');
            Route::get('/update-status', [SeekerSiteController::class, 'updateStatus'])->name('update.status');
            Route::get('/delete', [SeekerSiteController::class, 'deleteProfile'])->name('delete.profile');
        });
    });

    Route::prefix('employer')->name('employer.')->group(function () {
        Route::get('/login', [EmployerSiteController::class, 'login'])->name('login');
        Route::get('/register', [EmployerSiteController::class, 'register'])->name('register');
        Route::post('/post-login', [EmployerSiteController::class, 'postLogin'])->name('post.login');
        Route::post('/post-register', [EmployerSiteController::class, 'postRegister'])->name('post.register');
        Route::get('/confirm-acount', [EmployerSiteController::class, 'confirmAccount'])->name('confirm.acount');
        Route::get('/logout', [EmployerSiteController::class, 'logout'])->name('logout');

        Route::prefix('jobs')->name('job.')->middleware(['check.employer'])->group(function () {
            Route::get('/', [EmployerSiteController::class, 'manageJob'])->name('index');
            Route::get('/create', [EmployerSiteController::class, 'createJob'])->name('create');
            Route::get('/profile', [EmployerSiteController::class, 'profileEmployer'])->name('profile');
            Route::post('/update-profile/{id}', [EmployerSiteController::class, 'updateProfileEmployer'])->name('update.profile');
            Route::post('/store', [EmployerSiteController::class, 'storeJob'])->name('store');
            Route::get('/edit/{slug}/{id}', [EmployerSiteController::class, 'editJob'])->name('edit');
            Route::post('/update/{id}', [EmployerSiteController::class, 'updateJob'])->name('update');
            Route::get('/update-status', [EmployerSiteController::class, 'updateStatus'])->name('update.status');
            Route::get('/delete', [EmployerSiteController::class, 'deleteJob'])->name('delete');
            Route::get('/ho-so-ung-tuyen', [EmployerSiteController::class, 'manageApply'])->name('apply');
            Route::get('/ho-so-da-luu', [EmployerSiteController::class, 'manageFolow'])->name('folow');
        });
    });

    Route::prefix('cart')->name('cart.')->middleware('check.employer')->group(function(){
        Route::get('/',[CartSiteController::class,'index'])->name('index');
        Route::post('add',[CartSiteController::class,'add'])->name('add');
        Route::post('data',[CartSiteController::class,'getCartData'])->name('get.data');
        Route::post('get-cart',[CartSiteController::class,'getCartModal'])->name('get.modal');
        Route::post('delete/{id}',[CartSiteController::class,'deleteCart'])->name('delete');
        Route::post('update',[CartSiteController::class,'updateCart'])->name('update');
    });

    Route::prefix('checkout')->name('checkout.')->middleware('check.employer')->group(function(){
        Route::get('/',[CheckoutController::class,'index'])->name('index');
        Route::get('webhook/{id}',[CheckoutController::class,'webhook'])->name('webhook');
        Route::post('momo',[CheckoutController::class,'checkoutMoMo'])->name('momo');
        Route::post('add-order',[CheckoutController::class,'checkout'])->name('add');
        // Route::post('data',[CartSiteController::class,'getCartData'])->name('get.data');
        // Route::post('get-cart',[CartSiteController::class,'getCartModal'])->name('get.modal');
        // Route::post('delete/{id}',[CartSiteController::class,'deleteCart'])->name('delete');
        // Route::post('update',[CartSiteController::class,'updateCart'])->name('update');
    });

    Route::prefix('tin-tuyen-dung')->name('recruitment.')->group(function () {
        Route::get('/', [RecruitmentController::class, 'index'])->name('index');
        Route::get('/{slug}/{id}', [RecruitmentController::class, 'jobDetail'])->name('job.detail');
        Route::get('/search-information', [RecruitmentController::class, 'searchInformation'])->name('search.information');
        Route::post('/update-apply', [RecruitmentController::class, 'updateApply'])->name('update.apply');
        Route::get('/wishlist', [RecruitmentController::class, 'wishList'])->name('wishlist');
        Route::post('/apply-recruitment/{recruitment_id}', [RecruitmentController::class, 'apply'])->name('apply');
        Route::post('/apply-recruitment-for-email/{recruitment_id}', [RecruitmentController::class, 'applyForEmail'])->name('apply.for.email');
    });

    Route::prefix('tim-ho-so')->name('hoso.')->group(function () {
        Route::get('/', [HoSoSiteController::class, 'index'])->name('index');
        Route::get('/{slug}/{id}', [HoSoSiteController::class, 'hoSoDetail'])->name('detail');
        Route::get('/search-information', [HoSoSiteController::class, 'searchInformation'])->name('search.information');
        // Route::post('/update-apply',[HoSoSiteController::class,'updateApply'])->name('update.apply');
        Route::get('flow-user', [HoSoSiteController::class, 'flowUser'])->name('flow.user');
        // Route::post('apply-recruitment/{recruitment_id}',[HoSoSiteController::class,'apply'])->name('apply');
        // Route::post('apply-recruitment-for-email/{recruitment_id}',[HoSoSiteController::class,'applyForEmail'])->name('apply.for.email');
    });

    Route::prefix('service')->name('service.')->group(function(){
        Route::get('/',[ServiceSiteController::class,'index'])->name('index');
        Route::get('push-news',[ServiceSiteController::class,'pushNews'])->name('push.news')->can('push_news');
    });

    Route::prefix('coupon')->name('coupon.')->group(function(){
        Route::post('add',[CouponsController::class,'addCoupon'])->name('add');
        // Route::get('push-news',[ServiceSiteController::class,'pushNews'])->name('push.news')->can('push_news');
    });
});
