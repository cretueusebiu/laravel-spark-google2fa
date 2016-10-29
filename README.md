# Google Authenticator support for Laravel Spark

This package replaces the default Two-Factor Authentication driver with [Google Authenticator](https://support.google.com/accounts/answer/1066447?hl=en). 

![Demo](http://i.imgur.com/WQKx5nS.gif)

## Installation

- Run `composer require eusebiu/laravel-spark-google2fa`

- Add `Eusebiu\LaravelSparkGoogle2FA\Google2FAServiceProvider::class` to your `providers` array in `config/app.php` after `SparkServiceProvider`.

- Run `php artisan vendor:publish --provider="Eusebiu\LaravelSparkGoogle2FA\Google2FAServiceProvider" --tag=migrations --tag=assets`

- Run `composer dumpautoload`

- Run `php artisan migrate`

- Edit `resources/views/vendor/spark/settings/security/enable-two-factor-auth.blade.php` and replace everything with `@include('google2fa::enable-two-factor-auth')`

- Edit `resources/assets/js/spark-components/settings/security/enable-two-factor-auth.js` and replace `require('settings/security/enable-two-factor-auth')` with `require('./enable-two-factor-auth-google')`

- Run `gulp`
