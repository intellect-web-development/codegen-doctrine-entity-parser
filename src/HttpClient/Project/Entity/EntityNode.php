<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Project\Entity;

use IWD\CodeGen\CodegenDoctrineEntityParser\Enum\IdentificationType;
use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\HttpSdkClient;

class EntityNode
{
    private $client;

    public function __construct(
        HttpSdkClient $client
    ) {
        $this->client = $client;
    }

    public function create(
        string $boundedContextId,
        IdentificationType $identificationType,
        string $name,
        ?string $description = null,
        ?string $tableName = null,
        string $accessJwt = null
    ): array {
        $entity = $this->client->post(
            '/api/admin/project/entities/create',
            [
                'boundedContextId' => $boundedContextId,
                'identificationType' => $identificationType,
                'name' => $name,
                'description' => $description,
            ],
            [
                'Authorization' => "Bearer {$accessJwt}",
            ]
        );
        if (null !== $tableName) {
            $table = $this->client->post(
                '/api/admin/project/tables/edit',
                [
                    'id' => $entity['tableId'],
                    'name' => $tableName,
                ],
                [
                    'Authorization' => "Bearer {$accessJwt}",
                ]
            );
        }

        return $entity;
    }

    public function cruds(
        string $id,
        bool $create,
        bool $read,
        bool $update,
        bool $delete,
        bool $search,
        string $accessJwt = null
    ): array {
        return $this->client->post(
            '/api/admin/project/entities/cruds',
            [
                'id' => $id,
                'create' => $create,
                'read' => $read,
                'update' => $update,
                'delete' => $delete,
                'search' => $search,
            ],
            [
                'Authorization' => "Bearer {$accessJwt}",
            ]
        );
    }
}
