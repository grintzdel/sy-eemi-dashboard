<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Application\Commands\Login;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class LoginCommand
{
    public function __construct(
        #[Assert\NotBlank(message: 'Email cannot be empty')]
        #[Assert\Email(message: 'Email must be a valid email address')]
        private string $email,

        #[Assert\NotBlank(message: 'Password cannot be empty')]
        private string $password
    ) {}

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
