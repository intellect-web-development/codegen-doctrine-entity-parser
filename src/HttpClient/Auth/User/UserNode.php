<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Auth\User;

use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\HttpSdkClient;

readonly class UserNode
{
    public function __construct(
        private HttpSdkClient $client,
    ) {
    }

    public function create(
        string $email,
        string $password,
        string $name,
    ): array {
        return $this->client->post(
            uri: '/api/admin/auth/users/create',
            body: [
                'email' => $email,
                'plainPassword' => $password,
                'name' => $name,
            ]
        );
    }

    public function getJwt(
        string $email,
        string $password,
    ): array {
        return $this->client->post(
            uri: '/api/token/authentication',
            body: [
                'email' => $email,
                'password' => $password,
            ],
        );
    }
}
