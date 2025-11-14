<?php

declare(strict_types=1);

namespace App\Modules\User\Application\Commands\UpdateUser;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateUserCommand
{
    #[Assert\NotBlank(message: 'User ID is required')]
    #[Assert\Uuid(message: 'User ID must be a valid UUID')]
    private string $id;

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

    public function __construct(
        string $id,
        string $name,
        string $email
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
