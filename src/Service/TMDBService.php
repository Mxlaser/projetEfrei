<?php
// src/Service/TMDBService.php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class TMDBService
{
    private $apiKey;
    private $httpClient;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->httpClient = HttpClient::create();
    }

    public function searchMovies(string $query): array
    {
        $response = $this->httpClient->request('GET', 'https://api.themoviedb.org/3/search/movie', [
            'query' => [
                'api_key' => $this->apiKey,
                'query' => $query,
            ],
        ]);

        return $response->toArray()['results'];
    }
}
