<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Project\EntityAttribute;

use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\HttpSdkClient;
use App\Project\Domain\Entity\Enum\IdentificationType;
use App\Project\Domain\EntityAttribute\Embeddable\DefaultValue;
use App\Project\Domain\EntityAttribute\Enum\EntityAttributeSpecialAppointment;
use App\Project\Domain\TableAttribute\Enum\TableAttributeType;

readonly class EntityAttributeNode
{
    public function __construct(
        private HttpSdkClient $client,
    ) {
    }

    public function create(
        string $entityId,
        string $name,
        bool $nullable,
        bool $unique,
        ?string $description = null,
        bool $translatable = false,
        ?DefaultValue $defaultValue = null,
        EntityAttributeSpecialAppointment $specialAppointment = EntityAttributeSpecialAppointment::None,
        TableAttributeType $tableAttributeType = TableAttributeType::Undefined,
        string $tableAttributeName = null,
        int $tableAttributeLength = null,
        ?string $entityAttributeEnumId = null,
        string $accessJwt = null,
    ): array {
        $entityAttribute = $this->client->post(
            uri: '/api/admin/project/entity-attributes/create',
            body: [
                'entityId' => $entityId,
                'entityAttributeEnumId' => $entityAttributeEnumId,
                'defaultValueExists' => $defaultValue?->isExists() ?? false,
                'defaultValueValue' => $defaultValue?->getValue() ?? null,
                'description' => $description,
                'name' => $name,
                'nullable' => $nullable,
                'specialAppointment' => $specialAppointment->value,
                'translatable' => $translatable,
                'unique' => $unique,
            ],
            headers: [
                'Authorization' => "Bearer {$accessJwt}",
            ]
        );

        $tableAttribute = $this->client->post(
            uri: '/api/admin/project/table-attributes/edit',
            body: [
                'id' => $entityAttribute['tableAttributeId'],
                'type' => $tableAttributeType->value,
                'name' => $tableAttributeName,
                'length' => $tableAttributeLength,
            ],
            headers: [
                'Authorization' => "Bearer {$accessJwt}",
            ]
        );

        return $entityAttribute;
    }

    //todo: переделать на http
    public function createId(
        string $entityId,
        IdentificationType $identificationType,
        string $name = 'id',
        string $description = 'Entity ID',
        string $tableAttributeName = null,
        string $accessJwt = null,
    ): array {
        $entityAttribute = $this->client->post(
            uri: '/api/admin/project/entity-attributes/create',
            body: [
                'entityId' => $entityId,
                'entityAttributeEnumId' => null,
                'defaultValueExists' => false,
                'defaultValueValue' => null,
                'description' => $description,
                'name' => $name,
                'nullable' => false,
                'specialAppointment' => EntityAttributeSpecialAppointment::Identification->value,
                'translatable' => false,
                'unique' => true,
            ],
            headers: [
                'Authorization' => "Bearer {$accessJwt}",
            ]
        );

        $type = match ($identificationType) {
            IdentificationType::Sequence => TableAttributeType::Integer,
            IdentificationType::Uuid => TableAttributeType::Uuid,
        };

        $tableAttribute = $this->client->post(
            uri: '/api/admin/project/table-attributes/edit',
            body: [
                'id' => $entityAttribute['tableAttributeId'],
                'type' => $type->value,
                'name' => $tableAttributeName,
            ],
            headers: [
                'Authorization' => "Bearer {$accessJwt}",
            ]
        );

        return $entityAttribute;
    }

    public function createCreatedAt(
        string $entityId,
        string $name = 'createdAt',
        string $description = 'Entity created at',
        string $tableAttributeName = null,
        string $accessJwt = null,
    ): array {
        $entityAttribute = $this->client->post(
            uri: '/api/admin/project/entity-attributes/create',
            body: [
                'entityId' => $entityId,
                'entityAttributeEnumId' => null,
                'defaultValueExists' => false,
                'defaultValueValue' => null,
                'description' => $description,
                'name' => $name,
                'nullable' => false,
                'specialAppointment' => EntityAttributeSpecialAppointment::CreatedAt->value,
                'translatable' => false,
                'unique' => false,
            ],
            headers: [
                'Authorization' => "Bearer {$accessJwt}",
            ]
        );

        $tableAttribute = $this->client->post(
            uri: '/api/admin/project/table-attributes/edit',
            body: [
                'id' => $entityAttribute['tableAttributeId'],
                'type' => TableAttributeType::Datetime->value,
                'name' => $tableAttributeName,
            ],
            headers: [
                'Authorization' => "Bearer {$accessJwt}",
            ]
        );

        return $entityAttribute;
    }

    public function createUpdatedAt(
        string $entityId,
        string $name = 'updatedAt',
        string $description = 'Entity updated at',
        string $tableAttributeName = null,
        string $accessJwt = null,
    ): array {
        $entityAttribute = $this->client->post(
            uri: '/api/admin/project/entity-attributes/create',
            body: [
                'entityId' => $entityId,
                'entityAttributeEnumId' => null,
                'defaultValueExists' => false,
                'defaultValueValue' => null,
                'description' => $description,
                'name' => $name,
                'nullable' => false,
                'specialAppointment' => EntityAttributeSpecialAppointment::UpdatedAt->value,
                'translatable' => false,
                'unique' => false,
            ],
            headers: [
                'Authorization' => "Bearer {$accessJwt}",
            ]
        );

        $tableAttribute = $this->client->post(
            uri: '/api/admin/project/table-attributes/edit',
            body: [
                'id' => $entityAttribute['tableAttributeId'],
                'type' => TableAttributeType::Datetime->value,
                'name' => $tableAttributeName,
            ],
            headers: [
                'Authorization' => "Bearer {$accessJwt}",
            ]
        );

        return $entityAttribute;
    }
}
