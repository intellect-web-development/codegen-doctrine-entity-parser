<?php

namespace IWD\CodeGen\CodegenDoctrineEntityParser;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Exception;
use IWD\CodeGen\CodegenDoctrineEntityParser\Enum\Cardinality;
use IWD\CodeGen\CodegenDoctrineEntityParser\Enum\EntityAttributeSpecialAppointment;
use IWD\CodeGen\CodegenDoctrineEntityParser\Enum\Orientation;
use IWD\CodeGen\CodegenDoctrineEntityParser\Enum\TableAttributeType;
use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\CodeGenSdk;
use IWD\CodeGen\CodegenDoctrineEntityParser\Service\Inflector;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Uid\Uuid;
use Throwable;

class Parser
{
    public const SUCCESS = 0;
    public const FAILURE = 1;

    private $em;
    private $sdk;

    public function __construct(
        EntityManagerInterface $em,
        CodeGenSdk $sdk
    ) {
        $this->sdk = $sdk;
        $this->em = $em;
    }

    public function process(Command $command, SymfonyStyle $io): int
    {
        try {
            $user = $this->sdk->auth->user->create(
                $userEmail = ($command->userEmail ?? Uuid::v4()->__toString() . '@demo.com'),
                $userPassword = ($command->userPassword ?? md5(random_bytes(255))),
                $userEmail
            );
        } catch (Throwable $throwable) {
            $io->error('Create user is fail, reason: ' . $throwable->getMessage());

            return self::FAILURE;
        }

        try {
            $jwt = $this->sdk->auth->user->getJwt(
                $userEmail,
                $userPassword
            );
        } catch (Throwable $throwable) {
            $io->error('Can not get JWT token: ' . $throwable->getMessage());

            return self::FAILURE;
        }

        /** @var string $accessJwt */
        $accessJwt = $jwt['access'];

        try {
            $project = $this->sdk->project->project->create(
                (int) $user['id'],
                $command->projectName,
                null,
                $accessJwt
            );
        } catch (Throwable $throwable) {
            $io->error('Create project is fail, reason: ' . $throwable->getMessage());

            return self::FAILURE;
        }
        $io->success(sprintf(
            'Project #%s "%s" was created',
            $project['id'],
            $project['name']
        ));

        try {
            $domainModel = $this->sdk->project->domainModel->create(
                $project['id'],
                $command->domainModelName,
                null,
                $accessJwt
            );
        } catch (Throwable $throwable) {
            $io->error('Create DomainModel is fail, reason: ' . $throwable->getMessage());

            return self::FAILURE;
        }
        $io->success(sprintf(
            'DomainModel #%s "%s" was created',
            $domainModel['id'],
            $domainModel['name']
        ));

        $metadataHashMapByEntityId = [];
        $entitiesHashMapByNamespace = [];
        $entitiesHashMapByEntityId = [];
        $metadataFactory = $this->em->getMetadataFactory();
        $allMetadata = $metadataFactory->getAllMetadata();
        $totalEntities = count($allMetadata);

        $contexts = [];
        $entityByContextHashMap = [];

        foreach ($allMetadata as $item) {
            // Получение полного идентификатора сущности
            $entityName = $item->getName();

            // Разделение по префиксам
            $parts = explode('\\', $entityName);

            // Игнорирование префиксов
            while (count($parts) > 2 && in_array($parts[0], $command->ignorePrefixes)) {
                array_shift($parts);
            }

            // Получение ограниченного контекста
            $context = $parts[0];

            // Разложение сущностей по ограниченным контекстам
            $contexts[$context][] = $entityName;
        }

        $baseBoundedContext = null;
        if (empty($contexts)) {
            try {
                $baseBoundedContext = $this->sdk->project->boundedContext->create(
                    $domainModel['id'],
                    $command->baseBoundedContextName ?? $command->domainModelName,
                    null,
                    $accessJwt
                );
            } catch (Throwable $throwable) {
                $io->error('Create BoundedContext is fail, reason: ' . $throwable->getMessage());

                return self::FAILURE;
            }
            $io->success(sprintf(
                'BoundedContext #%s "%s" was created',
                $baseBoundedContext['id'],
                $baseBoundedContext['name']
            ));
        }
        foreach ($contexts as $contextName => $entityNames) {
            try {
                $boundedContext = $this->sdk->project->boundedContext->create(
                    $domainModel['id'],
                    (string) $contextName,
                    null,
                    $accessJwt
                );
                $contexts[$contextName]['boundedContext'] = $boundedContext;
            } catch (Throwable $throwable) {
                $io->error('Create BoundedContext is fail, reason: ' . $throwable->getMessage());

                return self::FAILURE;
            }
            $io->success(sprintf(
                'BoundedContext #%s "%s" was created',
                $boundedContext['id'],
                $boundedContext['name']
            ));
        }

        $io->success('Next start create entities');

        foreach ($allMetadata as $key => $metadata) {
            $entityIteration = $key + 1;
            $entityName = $this->parseEntityNameFromClassMetadata($metadata);

            $entity = $this->sdk->project->entity->create(
                $contexts[$entityByContextHashMap[$metadata->getName()]]['boundedContext']['id'] ?? $baseBoundedContext['id'],
                $command->entityIdentificationType,
                $entityName,
                null,
                null,
                $accessJwt
            );
            $entitiesHashMapByNamespace[$metadata->getName()] = $entity;
            $metadataHashMapByEntityId[$entity['id']] = $metadata;
            $entitiesHashMapByEntityId[$entity['id']] = $entity;

            $this->sdk->project->entityAttribute->createId(
                $entity['id'],
                $command->entityIdentificationType,
                'id',
                'Entity ID',
                null,
                $accessJwt
            );

            foreach ($metadata->fieldMappings as $fieldMapping) {
                if (isset($fieldMapping['id']) && true === $fieldMapping['id']) {
                    continue;
                }
                if ('createdAt' === $fieldMapping['fieldName']) {
                    $this->sdk->project->entityAttribute->createCreatedAt($entity['id'], 'createdAt', 'Entity created at', null, $accessJwt);
                    continue;
                }
                if ('updatedAt' === $fieldMapping['fieldName']) {
                    $this->sdk->project->entityAttribute->createUpdatedAt($entity['id'], 'updatedAt', 'Entity updated at', null, $accessJwt);
                    continue;
                }

                $specialAppointment = EntityAttributeSpecialAppointment::None;
                $tableAttributeType = TableAttributeType::Undefined;

                if ('string' === $fieldMapping['type']) {
                    $tableAttributeType = TableAttributeType::String;
                } elseif ('json' === $fieldMapping['type']) {
                    $tableAttributeType = TableAttributeType::Json;
                } elseif ('uuid' === $fieldMapping['type']) {
                    $tableAttributeType = TableAttributeType::Uuid;
                } elseif ('datetime_immutable' === $fieldMapping['type']) {
                    $tableAttributeType = TableAttributeType::Datetime;
                } elseif ('datetime' === $fieldMapping['type']) {
                    $tableAttributeType = TableAttributeType::Datetime;
                } elseif ('integer' === $fieldMapping['type']) {
                    $tableAttributeType = TableAttributeType::Integer;
                } elseif ('boolean' === $fieldMapping['type']) {
                    $tableAttributeType = TableAttributeType::Boolean;
                }

                $this->sdk->project->entityAttribute->create(
                    $entity['id'],
                    Inflector::camelize(str_replace('.', '_', $fieldMapping['fieldName'])),
                    (bool) ($fieldMapping['nullable'] ?? true),
                    (bool) ($fieldMapping['unique'] ?? false),
                    null,
                    false,
                    null,
                    $specialAppointment,
                    $tableAttributeType,
                    null,
                    null,
                    null,
                    $accessJwt
                );
            }

            $this->sdk->project->entity->cruds(
                $entity['id'],
                true,
                true,
                true,
                true,
                true,
                $accessJwt
            );
            $io->success("[$entityIteration / $totalEntities] Created Entity {$entityName}.");
        }

        $io->success('Start create relation');

        $assocPairsMap = [];
        foreach ($allMetadata as $metadata) {
            $associationMappings = $metadata->getAssociationMappings();
            if ([] === $associationMappings) {
                continue;
            }
            foreach ($associationMappings as $associationMapping) {
                $relationEntity = $entitiesHashMapByNamespace[$metadata->getName()];
                $relatedEntity = $entitiesHashMapByNamespace[$associationMapping['targetEntity']];
                $ids = [
                    $relationEntity['id'],
                    $relatedEntity['id'],
                ];
                sort($ids);
                $assocPairsMap[] = implode('_', $ids);
            }
        }
        $assocUniquePairsMap = array_values(array_unique($assocPairsMap));

        foreach ($assocUniquePairsMap as $assocPair) {
            [$relatedEntityId, $relationEntityId] = explode('_', $assocPair);

            $relationEntityMetadata = $metadataHashMapByEntityId[$relationEntityId];
            $relationEntity = $entitiesHashMapByEntityId[$relationEntityId];
            $relationEntityAssociationMappings = $relationEntityMetadata->associationMappings;

            $relatedEntityMetadata = $metadataHashMapByEntityId[$relatedEntityId];
            $relatedEntity = $entitiesHashMapByEntityId[$relatedEntityId];
            $relatedEntityAssociationMappings = $relatedEntityMetadata->associationMappings;

            $relatedEntityAssociationMappingInfo = null;
            $relationEntityAssociationMappingInfo = null;
            foreach ($relationEntityAssociationMappings as $relationEntityAssociationMapping) {
                if ($relationEntityAssociationMapping['targetEntity'] === $relatedEntityMetadata->getName()) {
                    $relationEntityAssociationMappingInfo = $relationEntityAssociationMapping;
                    break;
                }
            }
            foreach ($relatedEntityAssociationMappings as $relatedEntityAssociationMapping) {
                if ($relatedEntityAssociationMapping['targetEntity'] === $relationEntityMetadata->getName()) {
                    $relatedEntityAssociationMappingInfo = $relatedEntityAssociationMapping;
                    break;
                }
            }
            if (null === $relatedEntityAssociationMappingInfo || null === $relationEntityAssociationMappingInfo) {
                $orientation = Orientation::Unidirectional;
            } else {
                $orientation = Orientation::Bidirectional;
            }

            $ownerCardinality = null;
            $inverseCardinality = null;
            if (isset($relationEntityAssociationMappingInfo['type'])) {
                if (ClassMetadataInfo::ONE_TO_ONE === $relationEntityAssociationMappingInfo['type']) {
                    $ownerCardinality = Cardinality::One;
                    $inverseCardinality = Cardinality::One;
                }
                if (ClassMetadataInfo::MANY_TO_ONE === $relationEntityAssociationMappingInfo['type']) {
                    $ownerCardinality = Cardinality::Many;
                    $inverseCardinality = Cardinality::One;
                }
                if (ClassMetadataInfo::ONE_TO_MANY === $relationEntityAssociationMappingInfo['type']) {
                    $ownerCardinality = Cardinality::One;
                    $inverseCardinality = Cardinality::Many;
                }
                if (ClassMetadataInfo::MANY_TO_MANY === $relationEntityAssociationMappingInfo['type']) {
                    $ownerCardinality = Cardinality::Many;
                    $inverseCardinality = Cardinality::Many;
                }
            } elseif (isset($relatedEntityAssociationMappingInfo['type'])) {
                if (ClassMetadataInfo::ONE_TO_ONE === $relatedEntityAssociationMappingInfo['type']) {
                    $ownerCardinality = Cardinality::One;
                    $inverseCardinality = Cardinality::One;
                }
                if (ClassMetadataInfo::MANY_TO_ONE === $relatedEntityAssociationMappingInfo['type']) {
                    $ownerCardinality = Cardinality::One;
                    $inverseCardinality = Cardinality::Many;
                }
                if (ClassMetadataInfo::ONE_TO_MANY === $relatedEntityAssociationMappingInfo['type']) {
                    $ownerCardinality = Cardinality::Many;
                    $inverseCardinality = Cardinality::One;
                }
                if (ClassMetadataInfo::MANY_TO_MANY === $relatedEntityAssociationMappingInfo['type']) {
                    $ownerCardinality = Cardinality::Many;
                    $inverseCardinality = Cardinality::Many;
                }
            }

            if (null === $ownerCardinality || null === $inverseCardinality) {
                throw new Exception('Invalid case for relation type');
            }
            //todo: отрефакторить код что выше (там портянка)
            //todo: тут еще нужно будет по метадате доктрины определять кто из них owner, а кто inverse,
            // сейчас просто наобум лазаря это ставится.

            $ownerRequired = false;
            $inverseRequired = false;
            if (isset($relationEntityAssociationMappingInfo['joinColumns'])) {
                $ownerRequired = isset($relationEntityAssociationMappingInfo['joinColumns'][0]['nullable']) && !$relationEntityAssociationMappingInfo['joinColumns'][0]['nullable'];
            }
            if (isset($relatedEntityAssociationMappingInfo['joinColumns'])) {
                $inverseRequired = isset($relatedEntityAssociationMappingInfo['joinColumns'][0]['nullable']) && !$relatedEntityAssociationMappingInfo['joinColumns'][0]['nullable'];
            }

            $this->sdk->project->entityRelation->create(
                $orientation,
                null,
                $relationEntity['id'],
                $ownerCardinality,
                $ownerRequired,
                $relationEntityAssociationMappingInfo['orphanRemoval'] ?? false,
                $relatedEntity['id'],
                $inverseCardinality,
                $inverseRequired,
                $relatedEntityAssociationMappingInfo['orphanRemoval'] ?? false,
                $relationEntityAssociationMappingInfo['fieldName'] ?? null,
                $relatedEntityAssociationMappingInfo['fieldName'] ?? null,
                $accessJwt
            );
        }

        $io->success('Done create relation');

        $io->success("
        You credentials:
            email: {$userEmail}
            password: {$userPassword}
        ");

        return self::SUCCESS;
    }

    private function parseEntityNameFromClassMetadata(ClassMetadata $entity): string
    {
        $entityNameExplode = explode('\\', $entity->getName());

        return end($entityNameExplode);
    }

    private function removePrefixes(array $parts): array
    {
        $limitedContexts = [];

        foreach ($parts as $index => $part) {
            if ($index === 0) {
                // Пропускаем произвольные префиксы
                continue;
            }

            $limitedContexts[] = $part;
        }

        return $limitedContexts;
    }
}
