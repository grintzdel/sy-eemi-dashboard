<?php

declare(strict_types=1);

namespace App\Modules\Task\Application\Commands\DeleteTask;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class DeleteTaskCommand
{
    #[Assert\NotBlank(message: 'Task ID is required')]
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
