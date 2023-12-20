<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Project\EntityRelation;

use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\HttpSdkClient;

class EntityRelationNode
{
    private $client;

    public function __construct(
        HttpSdkClient $client
    ) {
        $this->client = $client;
    }

    public function create(
        string $orientation,
        ?string $description,
        string $ownerEntityId,
        string $ownerSideCardinality,
        bool $ownerSideRequired,
        bool $ownerSideOrphanRemoval,
        string $inverseEntityId,
        string $inverseSideCardinality,
        bool $inverseSideRequired,
        bool $inverseSideOrphanRemoval,
        ?string $ownerSideName = null,
        ?string $inverseSideName = null,
        string $accessJwt = null
    ): array {
        return $this->client->post(
            '/api/admin/project/entity-relations/create',
            [
                'ownerEntityId' => $ownerEntityId,
                'inverseEntityId' => $inverseEntityId,
                'description' => $description,
                'inverseSideName' => $inverseSideName,
                'inverseSideOrphanRemoval' => $inverseSideOrphanRemoval,
                'inverseSideRequired' => $inverseSideRequired,
                'orientation' => $orientation,
                'ownerSideCardinality' => $ownerSideCardinality,
                'inverseSideCardinality' => $inverseSideCardinality,
                'ownerSideName' => $ownerSideName,
                'ownerSideOrphanRemoval' => $ownerSideOrphanRemoval,
                'ownerSideRequired' => $ownerSideRequired,
            ],
            [
                'Authorization' => "Bearer {$accessJwt}",
            ]
        );
    }
}
