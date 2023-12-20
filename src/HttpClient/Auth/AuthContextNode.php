<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Auth;

use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Auth\User\UserNode;

class AuthContextNode
{
    public $user;

    public function __construct(
        UserNode $user
    ) {
        $this->user = $user;
    }
}
