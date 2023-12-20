<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Project\BoundedContext;

use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\HttpSdkClient;

class BoundedContextNode
{
    private $client;

    public function __construct(
        HttpSdkClient $client
    ) {
        $this->client = $client;
    }

    public function create(
        string $domainModelId,
        string $name,
        ?string $description = null,
        string $accessJwt = null
    ): array {
        return $this->client->post(
            '/api/admin/project/bounded-contexts/create',
            [
                'domainModelId' => $domainModelId,
                'description' => $description,
                'name' => $name,
            ],
            [
                'Authorization' => "Bearer {$accessJwt}",
            ]
        );
    }
}
