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
            $pantiProfile = null;
            
            if (Auth::check()) {
                // First try to get from panti_asuhan (legacy)
                $currentPanti = DB::table('panti_asuhan')->where('user_id', Auth::id())->first();
                
                // Also get PantiProfile for verification status
                $pantiProfile = \App\Models\PantiProfile::where('id_user', Auth::id())->first();
            }

            $view->with('currentPanti', $currentPanti);
            $view->with('pantiProfile', $pantiProfile);
        });
    }
}
