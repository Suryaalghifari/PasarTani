<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;

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
        View::composer('*', function ($view) {
            $user = session('user') ?? null;
            // Ambil dari session, atau dari DB, atau dari API
            if ($user) {
                // Generate foto_url
                if (!empty($user['foto'])) {
                    $user['foto_url'] = 'http://localhost:5000/uploads/' . ltrim($user['foto'], '/');
                } else {
                    $user['foto_url'] = asset('default-avatar.png');
                }
            }
            $view->with('user', $user);
        });
    }
}
