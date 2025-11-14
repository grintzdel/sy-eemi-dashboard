<?php

declare(strict_types=1);

namespace App\Modules\Task\Application\Commands\CreateTask;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateTaskCommand
{
    #[Assert\NotBlank(message: 'Task name is required')]
    #[Assert\Length(max: 255, maxMessage: 'Task name cannot exceed 255 characters')]
    private string $name;

    #[Assert\NotBlank(message: 'Task description is required')]
    #[Assert\Length(max: 5000, maxMessage: 'Task description cannot exceed 5000 characters')]
    private string $description;

    #[Assert\NotBlank(message: 'Project ID is required')]
    private string $projectId;

    #[Assert\Choice(choices: ['TODO', 'ON_GOING', 'DONE'], message: 'Invalid status')]
    private ?string $status;

    /** @var array<string>|null */
    private ?array $assignedTo;

    public function __construct(
        string $name,
        string $description,
        string $projectId,
        ?string $status = null,
        ?array $assignedTo = null
    )
    {
        $this->name = $name;
        $this->description = $description;
        $this->projectId = $projectId;
        $this->status = $status;
        $this->assignedTo = $assignedTo;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getProjectId(): string
    {
        return $this->projectId;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getAssignedTo(): ?array
    {
        return $this->assignedTo;
    }
}
