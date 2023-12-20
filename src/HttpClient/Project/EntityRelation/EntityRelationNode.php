<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Project\EntityRelation;

use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\HttpSdkClient;
use App\Project\Domain\EntityRelation\Enum\Cardinality;
use App\Project\Domain\EntityRelation\Enum\Orientation;

readonly class EntityRelationNode
{
    public function __construct(
        private HttpSdkClient $client,
    ) {
    }

    public function create(
        Orientation $orientation,
        ?string $description,
        string $ownerEntityId,
        Cardinality $ownerSideCardinality,
        bool $ownerSideRequired,
        bool $ownerSideOrphanRemoval,
        ?string $ownerSideName = null,
        string $inverseEntityId,
        Cardinality $inverseSideCardinality,
        bool $inverseSideRequired,
        bool $inverseSideOrphanRemoval,
        ?string $inverseSideName = null,
        string $accessJwt = null,
    ): array {
        return $this->client->post(
            uri: '/api/admin/project/entity-relations/create',
            body: [
                'ownerEntityId' => $ownerEntityId,
                'inverseEntityId' => $inverseEntityId,
                'description' => $description,
                'inverseSideName' => $inverseSideName,
                'inverseSideOrphanRemoval' => $inverseSideOrphanRemoval,
                'inverseSideRequired' => $inverseSideRequired,
                'orientation' => $orientation->value,
                'ownerSideCardinality' => $ownerSideCardinality->value,
                'inverseSideCardinality' => $inverseSideCardinality->value,
                'ownerSideName' => $ownerSideName,
                'ownerSideOrphanRemoval' => $ownerSideOrphanRemoval,
                'ownerSideRequired' => $ownerSideRequired,
            ],
            headers: [
                'Authorization' => "Bearer {$accessJwt}",
            ]
        );
    }
}
