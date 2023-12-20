<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Project\Entity;

use IWD\CodeGen\CodegenDoctrineEntityParser\Enum\IdentificationType;
use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\HttpSdkClient;

readonly class EntityNode
{
    public function __construct(
        private HttpSdkClient $client,
    ) {
    }

    public function create(
        string $boundedContextId,
        IdentificationType $identificationType,
        string $name,
        ?string $description = null,
        ?string $tableName = null,
        string $accessJwt = null,
    ): array {
        $entity = $this->client->post(
            uri: '/api/admin/project/entities/create',
            body: [
                'boundedContextId' => $boundedContextId,
                'identificationType' => $identificationType->value,
                'name' => $name,
                'description' => $description,
            ],
            headers: [
                'Authorization' => "Bearer {$accessJwt}",
            ]
        );
        if (null !== $tableName) {
            $table = $this->client->post(
                uri: '/api/admin/project/tables/edit',
                body: [
                    'id' => $entity['tableId'],
                    'name' => $tableName,
                ],
                headers: [
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
        string $accessJwt = null,
    ): array {
        return $this->client->post(
            uri: '/api/admin/project/entities/cruds',
            body: [
                'id' => $id,
                'create' => $create,
                'read' => $read,
                'update' => $update,
                'delete' => $delete,
                'search' => $search,
            ],
            headers: [
                'Authorization' => "Bearer {$accessJwt}",
            ]
        );
    }
}
