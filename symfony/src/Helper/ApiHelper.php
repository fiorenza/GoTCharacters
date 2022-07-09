<?php

namespace App\Helper;

use GuzzleHttp\Client;

class ApiHelper
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function get(string $endpoint, array $query = [], array $headers = [])
    {
        return $this->request('get', $endpoint, '', $query, $headers);
    }

    public function post(string $endpoint, string $payload = '', array $query = [], array $headers = [])
    {
        return $this->request('post', $endpoint, $payload, $query, $headers);
    }

    private function request(string $method, string $endpoint, string $payload = '', array $query = [], array $headers = [])
    {
        $response = $this->client->$method($endpoint, [
            'headers' => $headers,
            'query' => $query,
            'body'  => $payload,
        ]);

        return $response->getBody()->getContents();
    }
}