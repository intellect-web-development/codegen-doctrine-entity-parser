<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Project\BoundedContext;

use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\HttpSdkClient;

readonly class BoundedContextNode
{
    public function __construct(
        private HttpSdkClient $client,
    ) {
    }

    public function create(
        string $domainModelId,
        string $name,
        ?string $description = null,
        string $accessJwt = null,
    ): array {
        return $this->client->post(
            uri: '/api/admin/project/bounded-contexts/create',
            body: [
                'domainModelId' => $domainModelId,
                'description' => $description,
                'name' => $name,
            ],
            headers: [
                'Authorization' => "Bearer {$accessJwt}",
            ]
        );
    }
}
