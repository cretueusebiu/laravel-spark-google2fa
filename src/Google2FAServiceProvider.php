<?php

namespace Eusebiu\LaravelSparkGoogle2FA;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Spark\Spark;
use PragmaRX\Google2FAQRCode\Google2FA;

class Google2FAServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../migrations');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'google2fa');

        if ($this->app->runningInConsole()) {
            $this->definePublishing();
        }

        $this->defineRoutes();
    }

    /**
     * Define the publishable migrations and resources.
     *
     * @return void
     */
    protected function definePublishing()
    {
        $this->publishes([
            __DIR__.'/../resources/assets/js/enable-two-factor-auth.js' => resource_path('assets/js/spark-components/settings/security/enable-two-factor-auth.js'),
        ], 'assets');

        $this->publishes([
            __DIR__.'/../resources/views/enable-two-factor-auth.blade.php' => resource_path('views/vendor/spark/settings/security/enable-two-factor-auth.blade.php'),
        ], 'views');
    }

    /**
     * Define the routes.
     *
     * @return void
     */
    protected function defineRoutes()
    {
        if (! $this->app->routesAreCached()) {
            Route::group(['middleware' => 'web'], function () {
                Route::post('/settings/two-factor-auth-generate', TwoFactorAuthController::class.'@generate');
                Route::post('/settings/two-factor-auth-google', TwoFactorAuthController::class.'@enable2fa');
            });
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        Spark::swap('EnableTwoFactorAuth@handle', function ($user) {
            $user->forceFill([
                'google2fa_secret' => session()->pull('spark:twofactor:secret'),
            ])->save();

            return $user;
        });

        Spark::swap('DisableTwoFactorAuth@handle', function ($user) {
            $user->forceFill([
                'google2fa_secret' => null,
            ])->save();

            return $user;
        });

        Spark::swap('VerifyTwoFactorAuthToken@handle', function ($user, $code) {
            return (new Google2FA)->verifyKey($user->google2fa_secret, $code);
        });
    }
}
