<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Overtrue\EasySms\EasySms;

/**
 * Class EasySmsServiceProvider - EasySms 服务器提供者
 *
 * @package App\Providers
 */
class EasySmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(EasySms::class, function () {
            return new EasySms(config('services.easy_sms'));
        });

        $this->app->alias(EasySms::class, 'easySms');
    }
}