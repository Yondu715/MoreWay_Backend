<?php

namespace App\Infrastructure\Database\Models\Infrastructure\Database\Models\Application\DTO\In\Auth;

use App\Infrastructure\Database\Models\Infrastructure\Database\Models\Infrastructure\Http\Requests\Auth\LoginRequest;

class LoginDto
{
    public readonly string $email;
    public readonly string $password;

    public function __construct(
        string $email,
        string $password
    ) {
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * @param LoginRequest $loginRequest
     * @return self
     */
    public static function fromRequest(LoginRequest $loginRequest): self
    {
        return new self(
            email: $loginRequest->email,
            password: $loginRequest->password
        );
    }
}
