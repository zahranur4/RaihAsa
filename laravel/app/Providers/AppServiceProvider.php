<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share the current panti (orphanage) with all panti.* views
        View::composer('panti.*', function ($view) {
            $currentPanti = null;
            if (Auth::check()) {
                $currentPanti = DB::table('panti_asuhan')->where('user_id', Auth::id())->first();
            }

            $view->with('currentPanti', $currentPanti);
        });
    }
}
