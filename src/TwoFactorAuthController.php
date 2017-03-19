<?php

namespace Eusebiu\LaravelSparkGoogle2FA;

use Laravel\Spark\Spark;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use Laravel\Spark\Contracts\Interactions\Settings\Security\EnableTwoFactorAuth;
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
        # If the user defined a custom "2fa_name" detail, use that
        if (isset(Spark::$details['2fa_name'])) {
            $company = urlencode(Spark::$details['2fa_name']);
        }
        # Otherwise, see if the Vendor is filled in
        elseif (isset(Spark::$details['vendor'])) {
            $company = urlencode(Spark::$details['vendor']);
        }
        # If it isn't, use the domain name as 2FA name
        else {
            $company = url()->to('/');
        }

        return str_replace('200x200', '260x260', (new Google2FA)->getQRCodeGoogleUrl($company, $email, $secret));
    }
}
