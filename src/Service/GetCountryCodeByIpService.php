<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetCountryCodeByIpService
{
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function get($ipAddress)
    {
        $url = 'http://ip-api.com/json/' . $ipAddress;

        $response = $this->client->request('GET', $url, [
            'query' => [
                'lang' => 'ru'
            ],
        ]);
    }
}
