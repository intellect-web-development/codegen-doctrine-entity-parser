<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser;

use IWD\CodeGen\CodegenDoctrineEntityParser\Enum\IdentificationType;

readonly class Command
{
    public function __construct(
        public string $projectName,
        public string $domainModelName,
        public IdentificationType $entityIdentificationType,
        public ?string $baseBoundedContextName = null,
        public ?string $userEmail = null,
        public ?string $userPassword = null,
    ) {
    }
}
