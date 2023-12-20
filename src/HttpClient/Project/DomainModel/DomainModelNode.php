<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Project\DomainModel;

use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\HttpSdkClient;

class DomainModelNode
{
    private $client;

    public function __construct(
        HttpSdkClient $client
    ) {
        $this->client = $client;
    }

    public function create(
        string $projectId,
        string $name,
        ?string $description = null,
        string $accessJwt = null
    ): array {
        return $this->client->post(
            '/api/admin/project/domain-models/create',
            [
                'projectId' => $projectId,
                'description' => $description,
                'name' => $name,
            ],
            [
                'Authorization' => "Bearer {$accessJwt}",
            ]
        );
    }
}
