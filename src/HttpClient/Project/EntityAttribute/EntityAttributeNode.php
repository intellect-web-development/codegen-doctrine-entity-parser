<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Project\EntityAttribute;

use IWD\CodeGen\CodegenDoctrineEntityParser\Enum\EntityAttributeSpecialAppointment;
use IWD\CodeGen\CodegenDoctrineEntityParser\Enum\IdentificationType;
use IWD\CodeGen\CodegenDoctrineEntityParser\Enum\TableAttributeType;
use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\HttpSdkClient;
use IWD\CodeGen\CodegenDoctrineEntityParser\VO\DefaultValue;

class EntityAttributeNode
{
    private $client;

    public function __construct(
        HttpSdkClient $client
    ) {
        $this->client = $client;
    }

    public function create(
        string $entityId,
        string $name,
        bool $nullable,
        bool $unique,
        ?string $description = null,
        bool $translatable = false,
        ?DefaultValue $defaultValue = null,
        string $specialAppointment = EntityAttributeSpecialAppointment::None,
        string $tableAttributeType = TableAttributeType::Undefined,
        string $tableAttributeName = null,
        int $tableAttributeLength = null,
        ?string $entityAttributeEnumId = null,
        string $accessJwt = null
    ): array {
        $entityAttribute = $this->client->post(
            '/api/admin/project/entity-attributes/create',
            [
                'entityId' => $entityId,
                'entityAttributeEnumId' => $entityAttributeEnumId,
                'defaultValueExists' => $defaultValue ? $defaultValue->isExists() : false,
                'defaultValueValue' => $defaultValue ? $defaultValue->getValue() : null,

                'description' => $description,
                'name' => $name,
                'nullable' => $nullable,
                'specialAppointment' => $specialAppointment,
                'translatable' => $translatable,
                'unique' => $unique,
            ],
            [
                'Authorization' => "Bearer {$accessJwt}",
            ]
        );

        $tableAttribute = $this->client->post(
            '/api/admin/project/table-attributes/edit',
            [
                'id' => $entityAttribute['tableAttributeId'],
                'type' => $tableAttributeType,
                'name' => $tableAttributeName,
                'length' => $tableAttributeLength,
            ],
            [
                'Authorization' => "Bearer {$accessJwt}",
            ]
        );

        return $entityAttribute;
    }

    public function createId(
        string $entityId,
        string $identificationType,
        string $name = 'id',
        string $description = 'Entity ID',
        string $tableAttributeName = null,
        string $accessJwt = null
    ): array {
        $entityAttribute = $this->client->post(
            '/api/admin/project/entity-attributes/create',
            [
                'entityId' => $entityId,
                'entityAttributeEnumId' => null,
                'defaultValueExists' => false,
                'defaultValueValue' => null,
                'description' => $description,
                'name' => $name,
                'nullable' => false,
                'specialAppointment' => EntityAttributeSpecialAppointment::Identification,
                'translatable' => false,
                'unique' => true,
            ],
            [
                'Authorization' => "Bearer {$accessJwt}",
            ]
        );

        $type = null;
        switch ($identificationType) {
            case IdentificationType::Sequence:
                $type = TableAttributeType::Integer;
                break;
            case IdentificationType::Uuid:
                $type = TableAttributeType::Uuid;
                break;
        }

        $tableAttribute = $this->client->post(
            '/api/admin/project/table-attributes/edit',
            [
                'id' => $entityAttribute['tableAttributeId'],
                'type' => $type,
                'name' => $tableAttributeName,
            ],
            [
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
        string $accessJwt = null
    ): array {
        $entityAttribute = $this->client->post(
            '/api/admin/project/entity-attributes/create',
            [
                'entityId' => $entityId,
                'entityAttributeEnumId' => null,
                'defaultValueExists' => false,
                'defaultValueValue' => null,
                'description' => $description,
                'name' => $name,
                'nullable' => false,
                'specialAppointment' => EntityAttributeSpecialAppointment::CreatedAt,
                'translatable' => false,
                'unique' => false,
            ],
            [
                'Authorization' => "Bearer {$accessJwt}",
            ]
        );

        $tableAttribute = $this->client->post(
            '/api/admin/project/table-attributes/edit',
            [
                'id' => $entityAttribute['tableAttributeId'],
                'type' => TableAttributeType::Datetime,
                'name' => $tableAttributeName,
            ],
            [
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
        string $accessJwt = null
    ): array {
        $entityAttribute = $this->client->post(
            '/api/admin/project/entity-attributes/create',
            [
                'entityId' => $entityId,
                'entityAttributeEnumId' => null,
                'defaultValueExists' => false,
                'defaultValueValue' => null,
                'description' => $description,
                'name' => $name,
                'nullable' => false,
                'specialAppointment' => EntityAttributeSpecialAppointment::UpdatedAt,
                'translatable' => false,
                'unique' => false,
            ],
            [
                'Authorization' => "Bearer {$accessJwt}",
            ]
        );

        $tableAttribute = $this->client->post(
            '/api/admin/project/table-attributes/edit',
            [
                'id' => $entityAttribute['tableAttributeId'],
                'type' => TableAttributeType::Datetime,
                'name' => $tableAttributeName,
            ],
            [
                'Authorization' => "Bearer {$accessJwt}",
            ]
        );

        return $entityAttribute;
    }
}
