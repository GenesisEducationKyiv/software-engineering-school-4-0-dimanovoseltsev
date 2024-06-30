<?php

namespace tests\components;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class CurrencyClient extends Client
{
    /**
     * @param $uri
     * @param array $options
     * @return ResponseInterface
     */
    public function get($uri, array $options = []): ResponseInterface
    {
        return new Response(200, [], json_encode([
            "iso3" => "UAH",
            "rate" => 5,
            "updatedAt" => time()
        ]));
    }
}
