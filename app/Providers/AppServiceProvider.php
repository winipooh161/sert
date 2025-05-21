<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\SmsService;
use App\Services\ImageService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SmsService::class, function ($app) {
            return new SmsService();
        });
        
        // Регистрируем ImageService, если не зарегистрирован
        if (!$this->app->bound(ImageService::class)) {
            $this->app->singleton(ImageService::class, function ($app) {
                return new ImageService();
            });
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
