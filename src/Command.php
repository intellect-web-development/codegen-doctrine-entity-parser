<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser;

use IWD\CodeGen\CodegenDoctrineEntityParser\Enum\IdentificationType;

class Command
{
    public $projectName;
    public $domainModelName;
    public $entityIdentificationType;
    public $baseBoundedContextName = null;
    public $userEmail = null;
    public $userPassword = null;

    public function __construct(
        string $projectName,
        string $domainModelName,
        IdentificationType $entityIdentificationType,
        ?string $baseBoundedContextName = null,
        ?string $userEmail = null,
        ?string $userPassword = null
    ) {
        $this->userPassword = $userPassword;
        $this->userEmail = $userEmail;
        $this->baseBoundedContextName = $baseBoundedContextName;
        $this->entityIdentificationType = $entityIdentificationType;
        $this->domainModelName = $domainModelName;
        $this->projectName = $projectName;
    }
}
