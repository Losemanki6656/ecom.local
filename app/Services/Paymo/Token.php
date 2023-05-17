<?php


namespace App\Services\Paymo;


use App\Services\Paymo\Entities\Consumer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Token
{
    const BASE_URL = 'https://partner.atmos.uz/token';

    const USERNAME = 'PfgPGkJjNTfZStrfyTdzk92NjfAa';

    const PASSWORD = 'Sq1Rp3m7qTV9FfW5mIuyB4RywF0a';

    const CACHE_KEY = 'cache_key';

    const CACHE_SECONDS = 250;

    public function get() {
        if (Cache::has(self::CACHE_KEY)) {
            return Cache::get(self::CACHE_KEY);
        }
        $this->setNewToken();
        return Cache::get(self::CACHE_KEY);
    }

    private function setNewToken() {
        try{
            $consumer = new Consumer(self::USERNAME, self::PASSWORD);
            $username = $consumer->getUsername();
            $password = $consumer->getPassword();
            $base64_encoded_cred = base64_encode("$username:$password");
            $http = Http::asForm()->withHeaders([
                'content-type'  =>  'application/x-www-form-urlencoded',
                'accept'  =>  'application-json',
                'Authorization' => "Basic $base64_encoded_cred"
            ]);
            $response = $http
                ->timeout(3000)
                ->post(self::BASE_URL, [
                    'grant_type' => 'client_credentials'
                ]);
            $token = $response->json('access_token');
        }catch (\Exception $e) {
            throw new \DomainException('Get token error');
        }
        Cache::add(self::CACHE_KEY, $token, self::CACHE_SECONDS);
    }

}
