<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient;

use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Auth\AuthContextNode;
use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Project\ProjectContextNode;

readonly class CodeGenSdk
{
    public function __construct(
        public AuthContextNode $auth,
        public ProjectContextNode $project,
    ) {
    }
}
