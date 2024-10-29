<?php

namespace App\Services;

use GuzzleHttp\Client;

class WhatsAppService
{
    protected $client;
    protected $accessToken;

    public function __construct()
    {
        $this->client = new Client();
        $this->accessToken = env('WHATSAPP_ACCESS_TOKEN');
    }

    public function sendMessage($to, $message)
    {
        $url = "https://graph.facebook.com/v19.0/320582204475119/messages";
        
        $response = $this->client->post($url, [
            'headers' => [
                'Authorization' => "Bearer {$this->accessToken}",
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'text',
                'text' => [
                    'body' => $message,
                ],
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
}
