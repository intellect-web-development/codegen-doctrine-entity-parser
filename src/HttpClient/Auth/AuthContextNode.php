<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Auth;

use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Auth\User\UserNode;

readonly class AuthContextNode
{
    public function __construct(
        public UserNode $user,
    ) {
    }
}
