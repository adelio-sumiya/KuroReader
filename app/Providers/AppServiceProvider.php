<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Service\NovelAPIService ;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(NovelApiService::class, function ($app) {
            return new NovelApiService();
    });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
