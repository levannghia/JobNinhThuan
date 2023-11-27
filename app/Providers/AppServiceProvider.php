<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(125);
        // $category_noibat = Category::with('informations')->where('noi_bat', 1)->where('status', 1)->where('type', '!=', 1)->orderBy('order', 'ASC')->get();
        // view()->share([
        //     'category_noibat' => $category_noibat
        // ]);
    }
}
