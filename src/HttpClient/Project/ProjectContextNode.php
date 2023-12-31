<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Project;

use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Project\BoundedContext\BoundedContextNode;
use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Project\DomainModel\DomainModelNode;
use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Project\Entity\EntityNode;
use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Project\EntityAttribute\EntityAttributeNode;
use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Project\EntityRelation\EntityRelationNode;
use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Project\Project\ProjectNode;

class ProjectContextNode
{
    public $domainModel;
    public $project;
    public $boundedContext;
    public $entity;
    public $entityAttribute;
    public $entityRelation;

    public function __construct(
        ProjectNode $project,
        DomainModelNode $domainModel,
        BoundedContextNode $boundedContext,
        EntityNode $entity,
        EntityAttributeNode $entityAttribute,
        EntityRelationNode $entityRelation
    ) {
        $this->entityRelation = $entityRelation;
        $this->entityAttribute = $entityAttribute;
        $this->entity = $entity;
        $this->boundedContext = $boundedContext;
        $this->project = $project;
        $this->domainModel = $domainModel;
    }
}
