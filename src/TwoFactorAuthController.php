<?php

namespace Eusebiu\LaravelSparkGoogle2FA;

use Laravel\Spark\Spark;
use PragmaRX\Google2FA\Google2FA;
use Laravel\Spark\Http\Controllers\Settings\Security\TwoFactorAuthController as Controller;

class TwoFactorAuthController extends Controller
{
    /**
     * Store the two-factor authentication information on the user instance.
     *
     * @param  \EnableTwoFactorAuthRequest $request
     * @return string
     */
    protected function storeTwoFactorInformation($request)
    {
        $code = parent::storeTwoFactorInformation($request);

        return [
            'code' => $code,
            'qr' => $this->getQrCodeUrl($request->user()),
        ];
    }

    /**
     * Get the QR Code url for the given user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @return string
     */
    protected function getQrCodeUrl($user)
    {
        $company = isset(Spark::$details['vendor']) ? Spark::$details['vendor'] : url()->to('/');

        return (new Google2FA)->getQRCodeGoogleUrl(
            $company,
            $user->email,
            $user->google2fa_secret
        );
    }
}
