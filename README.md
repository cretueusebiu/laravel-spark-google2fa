# Google Authenticator support for Laravel Spark

This package replaces the default Two-Factor Authentication driver with [Google Authenticator](https://support.google.com/accounts/answer/1066447?hl=en). 

## Installation

- Run `composer require eusebiu/laravel-spark-google2fa`

- Add `Eusebiu\LaravelSparkGoogle2FA\Google2FAServiceProvider::class` to your `providers` array in `config/app.php`

- Run `php artisan vendor:publish`

- Run `php artisan migrate`

- Edit `resources/views/vendor/spark/settings/security/modals/two-factor-reset-code.blade.php` and replace everything with `@include('google2fa::qr-code-modal')` or the contents of the [qr-code-modal.blade.php](resources/views/qr-code-modal.blade.php) file.

Note: The country code and phone number under the user settings are no longer relevent and you can remove them.
