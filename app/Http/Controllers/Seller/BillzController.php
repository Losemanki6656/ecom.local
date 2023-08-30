<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;

class BillzController extends Controller
{

    public function getToken()
    {
        $secret = "diMpUNMMUurBiiTfBJQzVETuYYWmQdPjWDexYkhVoEJBMaujGrG6Ljnchj04RdwrWvyHTBbp5AAIE5DEPcDCncmdLEirndMKziIfbvYUQhzQwPhvLdoEeiUjaHyGeoBNH3NfibYfICTIRSYxiWVtLHUapHmfjyiYroWEsFBuGhnwAt3iEUYrvvNXsALRBhEyrpnjXZtN13pLAcIucmwe7WVioHVRMeTWwZFxTAwhCPcVXWRumHwvEzHexiBkenKZHVJ7cFBEByBsTncaPtn6EBBTih3xMHhjk4pBbsyMHXMWMjFnBnBjomTQCRspmiNmLrtUfAMwrZNSJGhjUJiBTBYndHrNbmYSoFsSItJSnpbXtazWBzrfjnypXjjfLmpeFGktyQC3YjXmbyjehGTjYWiLpBYvEzw7vmQRrbdzjo6JmHveQDQLIYrUHjNDQX0UwPdRAneRvmTke1rFdPHn8MoDweT2Y1TFdVBLvywmoJwMcHAKYVpoKoXsuXXyRXwy";

        $headerArray = array(
            'typ' => 'JWT',
            'alg' => 'HS256'
        );
        $payloadArray = array(
            'iss' => 'https://api.billz.uz/v1/',
            'iat' => 1638553677,
            'exp' => 1738553677,
            'sub' => 'demoshop.unimart'
        );

        $header = $this->base64_url_encode(json_encode($headerArray, JSON_FORCE_OBJECT));
        $payload = $this->base64_url_encode(json_encode($payloadArray, JSON_FORCE_OBJECT));

        $unsignedToken = $header . '.' . $payload;

        $signature = hash_hmac("sha256", $unsignedToken, $secret, true);
        $encodedSignature = $this->base64_url_encode($signature);
        $token = $unsignedToken . '.' . $encodedSignature;
        return $token;
    }

    function base64_url_encode($input)
    {
        return trim(strtr(base64_encode($input), '+/', '-_'), '=');
    }
}