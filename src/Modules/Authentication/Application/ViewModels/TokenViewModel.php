<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Application\ViewModels;

final readonly class TokenViewModel
{
    public function __construct(
        public string $token,
        public int $expiresIn
    ) {}

    public static function create(string $token, int $expiresIn): self
    {
        return new self($token, $expiresIn);
    }
}
