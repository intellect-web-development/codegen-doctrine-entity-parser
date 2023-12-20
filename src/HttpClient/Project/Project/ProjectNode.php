<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Project\Project;

use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\HttpSdkClient;

readonly class ProjectNode
{
    public function __construct(
        private HttpSdkClient $client,
    ) {
    }

    public function create(
        int $userId,
        string $name,
        ?string $description = null,
        string $accessJwt = null,
    ): array {
        return $this->client->post(
            uri: '/api/admin/project/projects/create',
            body: [
                'userId' => $userId,
                'name' => $name,
                'description' => $description,
            ],
            headers: [
                'Authorization' => "Bearer {$accessJwt}",
            ]
        );
    }
}
