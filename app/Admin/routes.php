<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->get('update-column', 'FunctionController@statusColumn')->name('update.status.column');
    $router->resource('categories', CategoryController::class);
    $router->resource('information', InformationController::class);
    $router->resource('ho-so-xin-viec', HoSoXinViecController::class);
    $router->resource('recruitments', RecruitmentController::class);
    $router->resource('setting', ConfigController::class);
    $router->resource('seeker', SeekerController::class);
    $router->resource('employers', EmployerController::class);
    $router->resource('packages', PackageController::class);
    $router->resource('services', ServiceController::class);
    
    $router->group([
        'prefix' => 'api',
    ],function(Router $router){
        $router->get('provinces','SeekerController@provinces');
    });

});
