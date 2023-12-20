<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\Auth\User;

use IWD\CodeGen\CodegenDoctrineEntityParser\HttpClient\HttpSdkClient;

class UserNode
{
    private $client;

    public function __construct(
        HttpSdkClient $client
    ) {
        $this->client = $client;
    }

    public function create(
        string $email,
        string $password,
        string $name
    ): array {
        return $this->client->post(
            '/api/admin/auth/users/create',
            [
                'email' => $email,
                'plainPassword' => $password,
                'name' => $name,
            ]
        );
    }

    public function getJwt(
        string $email,
        string $password
    ): array {
        return $this->client->post(
            '/api/token/authentication',
            [
                'email' => $email,
                'password' => $password,
            ]
        );
    }
}
