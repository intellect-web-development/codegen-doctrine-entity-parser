<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Project\Project;

use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\HttpSdkClient;

class ProjectNode
{
    private $client;

    public function __construct(
        HttpSdkClient $client
    ) {
        $this->client = $client;
    }

    public function create(
        int $userId,
        string $name,
        ?string $description = null,
        string $accessJwt = null
    ): array {
        return $this->client->post(
            '/api/admin/project/projects/create',
            [
                'userId' => $userId,
                'name' => $name,
                'description' => $description,
            ],
            [
                'Authorization' => "Bearer {$accessJwt}",
            ]
        );
    }
}
