<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient;

use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use Symfony\Component\Serializer\SerializerInterface;

class HttpSdkClient
{
    private $client;
    private $serializer;
    private $host;

    public function __construct(
        ClientInterface $client,
        SerializerInterface $serializer,
        string $host
    ) {
        $this->host = $host;
        $this->serializer = $serializer;
        $this->client = $client;
    }

    public function post(
        string $uri,
        array $body = [],
        array $headers = []
    ): array {
        $uri = rtrim($this->host, '/') . '/' . ltrim($uri, '/');
        $bodyPayload = $this->serializer->serialize($body, 'json');
        $response = $this->client->request(
            'POST',
            $uri,
            [
                RequestOptions::HEADERS => $headers,
                RequestOptions::BODY => $bodyPayload,
            ]
        );

        $payload = json_decode(
            $raw = $response->getBody()->getContents(),
            true
        );
        if (true === $payload['ok']) {
            return $payload['data'];
        }

        throw new Exception($raw);
    }
}
