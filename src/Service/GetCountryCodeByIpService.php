<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetCountryCodeByIpService
{
    public function __construct(
        private HttpClientInterface $client,
        private $configs,
    )
    {}

    public function get($ipAddress)
    {

        $response = $this->client
            ->request(
                $this->configs['method']['name'],
                $this->getFormattedUrl($ipAddress),
                $this->prepareParametres());

        if ($this->isResponseFailed($response)) {
            return ['errors' => 'Внешний API не отвечает!'];
        }

        return $response->toArray()[$this->configs['country_code']];
    }

    protected function prepareParametres()
    {
        if (empty(json_decode($this->configs['method']['parameters']))) {
            return [];
        }

        if($this->configs['method']['name'] == 'GET') {
            $requestKey = 'query';
        }

        if($this->configs['method']['name'] == 'POST') {
            $requestKey = 'body';
        }

        return [ $requestKey => json_decode($this->configs['method']['parameters'])];
    }

    protected function isResponseFailed($response)
    {
        if ($response->getStatusCode() !== 200) {
            return true;
        }

        [$key, $value] = explode(':',$this->configs['fail']);


        if (!empty($response->toArray()[$key]) && $response->toArray()[$key] === $value) {
            return true;
        }

        return false;
    }

    protected function getFormattedUrl(string $ip)
    {
        return str_replace('{ip}', $ip, $this->configs['url_format']);
    }
}
