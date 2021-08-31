<?php

namespace VCComponent\Laravel\Promotion\Providers;

use Illuminate\Support\ServiceProvider;
use VCComponent\Laravel\Promotion\Repositories\PromotionInterface;
use VCComponent\Laravel\Promotion\Repositories\PromotionReponsitory;

class PromotionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        $this->publishes([
            __DIR__ . '/../../config/promotion.php' => config_path('promotion.php'),
        ], 'config');

    }

    /**
     * Register any package services
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(PromotionInterface::class, PromotionReponsitory::class);
    }
}
