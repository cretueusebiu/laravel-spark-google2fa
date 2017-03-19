# Google Authenticator support for Laravel Spark

This package replaces the default Two-Factor Authentication driver with [Google Authenticator](https://support.google.com/accounts/answer/1066447?hl=en).

![Demo](http://i.imgur.com/WQKx5nS.gif)

## Installation

- Run `composer require eusebiu/laravel-spark-google2fa`

- Add `Eusebiu\LaravelSparkGoogle2FA\Google2FAServiceProvider::class` to your `providers` array in `config/app.php` after `SparkServiceProvider`.

- Run `php artisan config:clear`

- Run `php artisan vendor:publish --provider="Eusebiu\LaravelSparkGoogle2FA\Google2FAServiceProvider" --tag=migrations --tag=assets`

- Run `composer dumpautoload`

- Run `php artisan migrate`

- Edit `resources/views/vendor/spark/settings/security/enable-two-factor-auth.blade.php` and replace everything with `@include('google2fa::enable-two-factor-auth')`

- Edit `resources/assets/js/spark-components/settings/security/enable-two-factor-auth.js` and replace `require('settings/security/enable-two-factor-auth')` with `require('./enable-two-factor-auth-google')`

- Run `npm run dev`

## Customise the display name

The display name in the authenticator app is chosen from the ```$details``` section in your ```SparkServiceProvider``` file.

The following order is used, the first variable that's found will be the display name.

1. ```$details['2fa_name']``` (not present by default)
2. ```$details['vendor']``` (present by default)
3. the domain name (safe fallback)
