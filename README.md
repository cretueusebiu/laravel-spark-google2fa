# Google Authenticator for Laravel Spark 6

This package replaces the default two-factor authentication driver with [Google Authenticator](https://support.google.com/accounts/answer/1066447?hl=en).

![Demo](http://i.imgur.com/WQKx5nS.gif)

## Installation

- `composer require eusebiu/laravel-spark-google2fa`
- `php artisan vendor:publish --provider="Eusebiu\LaravelSparkGoogle2FA\Google2FAServiceProvider" --force`
- `php artisan migrate`
- `npm run dev`

## Customization

To change the display name in the authenticator app, add `2fa_name` in your `SparkServiceProvider` file. By default `vendor` or the app url is used.
