<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Project\DomainModel;

use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\HttpSdkClient;

readonly class DomainModelNode
{
    public function __construct(
        private HttpSdkClient $client,
    ) {
    }

    public function create(
        string $projectId,
        string $name,
        ?string $description = null,
        string $accessJwt = null,
    ): array {
        return $this->client->post(
            uri: '/api/admin/project/domain-models/create',
            body: [
                'projectId' => $projectId,
                'description' => $description,
                'name' => $name,
            ],
            headers: [
                'Authorization' => "Bearer {$accessJwt}",
            ]
        );
    }
}
