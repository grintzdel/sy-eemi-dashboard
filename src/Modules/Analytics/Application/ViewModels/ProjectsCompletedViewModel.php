<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Application\ViewModels;

final readonly class ProjectsCompletedViewModel
{
    public function __construct(
        public int $totalProjects,
        public int $completedProjects,
        public float $completionRate,
        public int $inProgressProjects,
        public int $todoProjects,
    ) {}
}
