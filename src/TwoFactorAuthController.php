<?php

namespace Eusebiu\LaravelSparkGoogle2FA;

use Laravel\Spark\Spark;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use Laravel\Spark\Http\Controllers\Settings\Security\TwoFactorAuthController as Controller;

class TwoFactorAuthController extends Controller
{
    /**
     * Generate the QR code for the user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function generateQrCode(Request $request)
    {
        $g2fa = new Google2FA;

        session()->put('spark:twofactor:secret', $secret = $g2fa->generateSecretKey());

        return $this->getQrUrl($request->user()->email, $secret);
    }

    /**
     * Enable two-factor authentication for the user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function enableTwoFactor(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|google2fa:'.session()->get('spark:twofactor:secret')
        ]);

        Spark::interact(EnableTwoFactorAuth::class, [$request->user()]);

        return $this->storeTwoFactorInformation($request);
    }

    /**
     * Get the qr code rul.
     *
     * @param  string $email
     * @param  string $secret
     * @return string
     */
    protected function getQrUrl($email, $secret)
    {
        $company = isset(Spark::$details['vendor']) ? urlencode(Spark::$details['vendor']) : url()->to('/');

        return str_replace('200x200', '260x260', (new Google2FA)->getQRCodeGoogleUrl($company, $email, $secret));
    }
}
