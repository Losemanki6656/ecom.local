<?php


namespace App\Services\Paymo;


use App\Services\Paymo\Token;
use Illuminate\Support\Facades\Http;

class PaymoRequest
{
    private $url;

    private $token;

    private $body;

    /**
     * PaymoRequest constructor.
     * @param $url
     * @param $body
     * @param Token $token
     */
    public function __construct($url, $body, Token $token)
    {
        $this->url = $url;
        $this->token = $token->get();
        $this->body = $body;
    }

    public function sendRequest() {
        $http = Http::withHeaders([
            'Content-Type'  =>  'application/json'
        ]);
        try {
            $response = $http
                ->withToken($this->token)
                ->timeout(3000)
                ->post($this->url, $this->body);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return response()->json([
                'message' =>  $e->getMessage()
            ], 500);
        }

        $statusCode = $response->status();
        $result = [];

        if($statusCode >= 400 && $statusCode <= 500){
            switch ($statusCode) {
                case 401:
                    $result['message'] = 'Unauthorized';
                    break;
                case 403:
                    $result['message'] = 'Forbidden';
                    break;
                case 404:
                    $result['message'] = 'Not Found';
                    break;
                case 405:
                    $result['message'] = 'Method Not Allowed';
                    break;
                default:
                    $result['message'] = ($statusCode == 500) ? $response->result->description : '500 error';
                break;
            }

            return response()->json([
                'message' =>  $result['message']
            ], $statusCode);
        }

        return $response->json();
    }

}
