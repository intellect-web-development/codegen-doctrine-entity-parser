<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient;

use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use Symfony\Component\Serializer\SerializerInterface;

readonly class HttpSdkClient
{
    public function __construct(
        private ClientInterface $client,
        private SerializerInterface $serializer,
        private string $host,
    ) {
    }

    public function post(
        string $uri,
        array $body = [],
        array $headers = [],
    ): array {
        $uri = rtrim($this->host, '/') . '/' . ltrim($uri, '/');
        $bodyPayload = $this->serializer->serialize($body, 'json');
        $response = $this->client->request(
            method: 'POST',
            uri: $uri,
            options: [
                RequestOptions::HEADERS => $headers,
                RequestOptions::BODY => $bodyPayload,
            ],
        );

        $payload = json_decode(
            $raw = $response->getBody()->getContents(),
            true,
            JSON_THROW_ON_ERROR,
        );
        if (true === $payload['ok']) {
            return $payload['data'];
        }

        throw new Exception($raw);
    }
}
