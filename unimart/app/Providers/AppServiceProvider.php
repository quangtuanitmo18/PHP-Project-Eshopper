<?php

namespace App\Providers;

use App\Slider;
use Illuminate\Support\ServiceProvider;

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
        //c1
        view()->composer('*', function ($view) {
            $slider = Slider::all();
            $view->with(compact('slider'));
        });

        //c2
        // $slider = Slider::all();
        // view()->share(compact('slider'));
    }
}
