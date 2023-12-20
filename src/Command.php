<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser;

class Command
{
    public $projectName;
    public $domainModelName;
    public $entityIdentificationType;
    public $baseBoundedContextName = null;
    public $userEmail = null;
    public $userPassword = null;
    /** @var array */
    public $ignorePrefixes;
    public $needCreateCustomBoundedContexts;

    public function __construct(
        string $projectName,
        string $domainModelName,
        string $entityIdentificationType,
        ?string $baseBoundedContextName = null,
        ?string $userEmail = null,
        ?string $userPassword = null,
        bool $needCreateCustomBoundedContexts = true,
        array $ignorePrefixes = []
    ) {
        $this->userPassword = $userPassword;
        $this->userEmail = $userEmail;
        $this->baseBoundedContextName = $baseBoundedContextName;
        $this->entityIdentificationType = $entityIdentificationType;
        $this->domainModelName = $domainModelName;
        $this->projectName = $projectName;
        $this->ignorePrefixes = $ignorePrefixes;
        $this->needCreateCustomBoundedContexts = $needCreateCustomBoundedContexts;
    }
}
