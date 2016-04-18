<?php

namespace Eusebiu\LaravelSparkGoogle2FA;

use Laravel\Spark\Spark;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\ServiceProvider;

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

        $this->publishes([
            __DIR__.'/../migrations' => database_path('migrations'),
        ], 'migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Laravel\Spark\Http\Controllers\Settings\Security\TwoFactorAuthController', TwoFactorAuthController::class);

        Spark::swap('EnableTwoFactorAuth@handle', function ($user, $country, $phone) {
            $user->forceFill([
                'google2fa_secret' => (new Google2FA)->generateSecretKey(),
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
            return Google2FA::verifyKey($user->google2fa_secret, $token);
        });
    }
}
