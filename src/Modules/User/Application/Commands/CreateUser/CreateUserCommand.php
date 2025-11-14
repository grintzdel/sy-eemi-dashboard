<?php

declare(strict_types=1);

namespace App\Modules\User\Application\Commands\CreateUser;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateUserCommand
{
    #[Assert\NotBlank(message: 'Username is required')]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Username must be at least {{ limit }} characters',
        maxMessage: 'Username cannot exceed {{ limit }} characters'
    )]
    private string $name;

    #[Assert\NotBlank(message: 'Email is required')]
    #[Assert\Email(message: 'Email must be a valid email address')]
    #[Assert\Length(max: 255, maxMessage: 'Email cannot exceed {{ limit }} characters')]
    private string $email;

    #[Assert\NotBlank(message: 'Password is required')]
    #[Assert\Length(
        min: 8,
        minMessage: 'Password must be at least {{ limit }} characters'
    )]
    private string $password;

    #[Assert\Choice(
        choices: ['ROLE_USER', 'ROLE_ARTIST', 'ROLE_MODERATOR', 'ROLE_ADMIN'],
        message: 'Invalid role'
    )]
    private string $role;

    public function __construct(
        string $name,
        string $email,
        string $password,
        string $role = 'ROLE_USER'
    )
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

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
