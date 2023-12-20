<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient;

use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Auth\AuthContextNode;
use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Project\ProjectContextNode;

class CodeGenSdk
{
    public $auth;
    public $project;

    public function __construct(
        AuthContextNode $auth,
        ProjectContextNode $project
    ) {
        $this->project = $project;
        $this->auth = $auth;
    }
}
