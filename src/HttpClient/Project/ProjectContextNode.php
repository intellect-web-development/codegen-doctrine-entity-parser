<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Project;

use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Project\BoundedContext\BoundedContextNode;
use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Project\DomainModel\DomainModelNode;
use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Project\Entity\EntityNode;
use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Project\EntityAttribute\EntityAttributeNode;
use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Project\EntityRelation\EntityRelationNode;
use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Project\Project\ProjectNode;

readonly class ProjectContextNode
{
    public function __construct(
        public ProjectNode $project,
        public DomainModelNode $domainModel,
        public BoundedContextNode $boundedContext,
        public EntityNode $entity,
        public EntityAttributeNode $entityAttribute,
        public EntityRelationNode $entityRelation,
    ) {
    }
}
