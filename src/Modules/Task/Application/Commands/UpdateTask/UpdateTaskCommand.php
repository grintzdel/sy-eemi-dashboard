<?php

declare(strict_types=1);

namespace App\Modules\Task\Application\Commands\UpdateTask;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateTaskCommand
{
    #[Assert\NotBlank(message: 'Task ID is required')]
    private string $id;

    #[Assert\NotBlank(message: 'Task name is required')]
    #[Assert\Length(max: 255, maxMessage: 'Task name cannot exceed 255 characters')]
    private string $name;

    #[Assert\NotBlank(message: 'Task description is required')]
    #[Assert\Length(max: 5000, maxMessage: 'Task description cannot exceed 5000 characters')]
    private string $description;

    public function __construct(string $id, string $name, string $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
