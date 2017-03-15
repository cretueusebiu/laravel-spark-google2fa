<?php

namespace Eusebiu\LaravelSparkGoogle2FA;

use Laravel\Spark\Spark;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class Google2FAServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'google2fa');

        if ($this->app->runningInConsole()) {
            $this->definePublishing();
        }

        $this->defineRoutes();

        $this->registerValidator();
    }

    /**
     * Define the publishable migrations and resources.
     *
     * @return void
     */
    protected function definePublishing()
    {
        if (! class_exists('AddUsersGoogle2faSecretColumn')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../migrations/add_users_google2fa_secret_column.php.stub' =>
                    $this->app->databasePath().'/migrations/'.$timestamp.'_add_users_google2fa_secret_column.php',
            ], 'migrations');
        }

        $this->publishes([
            __DIR__ . '/../resources/assets/js/enable-two-factor-auth-google.js' =>
                resource_path('assets/js/spark-components/settings/security/enable-two-factor-auth-google.js')
        ], 'assets');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/google2fa'),
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
            Route::group(['middleware' => 'web'], function ($router) {
                $router->post('/settings/two-factor-auth-generate', TwoFactorAuthController::class.'@generateQrCode');
                $router->post('/settings/two-factor-auth-google', TwoFactorAuthController::class.'@enableTwoFactor');
            });
        }
    }

    /**
     * Register the custom validator.
     *
     * @return void
     */
    protected function registerValidator()
    {
        Validator::extend('google2fa', function ($attribute, $value, $parameters) {
            return (new Google2FA)->verifyKey($parameters[0], $value);
        }, 'The code is invalid.');
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

        Spark::swap('VerifyTwoFactorAuthToken@handle', function ($user, $token) {
            return (new Google2FA)->verifyKey($user->google2fa_secret, $token);
        });
    }
}
