<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Application\Commands\Register;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class RegisterCommand
{
    public function __construct(
        #[Assert\NotBlank(message: 'Name cannot be empty')]
        #[Assert\Length(
            min: 3,
            max: 50,
            minMessage: 'Name must be at least {{ limit }} characters',
            maxMessage: 'Name cannot exceed {{ limit }} characters'
        )]
        private string $name,

        #[Assert\NotBlank(message: 'Email cannot be empty')]
        #[Assert\Email(message: 'Email must be a valid email address')]
        private string $email,

        #[Assert\NotBlank(message: 'Password cannot be empty')]
        #[Assert\Length(
            min: 8,
            minMessage: 'Password must be at least {{ limit }} characters'
        )]
        private string $password,

        #[Assert\Choice(
            choices: ['ROLE_ADMIN', 'ROLE_MODERATOR', 'ROLE_ARTIST', 'ROLE_USER'],
            message: 'Invalid role'
        )]
        private string $role = 'ROLE_USER'
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRole(): string
    {
        return $this->role;
    }
}
