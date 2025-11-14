<?php

declare(strict_types=1);

namespace App\Modules\Task\Application\Commands\UpdateTaskStatus;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateTaskStatusCommand
{
    #[Assert\NotBlank(message: 'Task ID is required')]
    private string $id;

    #[Assert\NotBlank(message: 'Status is required')]
    #[Assert\Choice(choices: ['TODO', 'ON_GOING', 'DONE'], message: 'Invalid status')]
    private string $status;

    public function __construct(string $id, string $status)
    {
        $this->id = $id;
        $this->status = $status;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
