<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Application\Queries\GetProjectsCompletedByPeriod;

use App\Modules\Analytics\Application\ViewModels\ProjectsCompletedViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetProjectsCompletedByPeriodQueryHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(
        GetProjectsCompletedByPeriodQuery $query,
    ): ProjectsCompletedViewModel {
        $sql = <<<SQL
            SELECT
                COUNT(*) as total_projects,
                SUM(CASE WHEN status = 'DONE' THEN 1 ELSE 0 END) as completed_projects,
                SUM(CASE WHEN status = 'ON_GOING' THEN 1 ELSE 0 END) as in_progress_projects,
                SUM(CASE WHEN status = 'TODO' THEN 1 ELSE 0 END) as todo_projects
            FROM projects
            WHERE deleted_at IS NULL
        SQL;

        $stmt = $this->entityManager->getConnection()->prepare($sql);
        $result = $stmt->executeQuery();
        $row = $result->fetchAssociative();

        $totalProjects = (int) $row['total_projects'];
        $completedProjects = (int) $row['completed_projects'];
        $completionRate =
            $totalProjects > 0
                ? round(($completedProjects / $totalProjects) * 100, 1)
                : 0.0;

        return new ProjectsCompletedViewModel(
            totalProjects: $totalProjects,
            completedProjects: $completedProjects,
            completionRate: $completionRate,
            inProgressProjects: (int) $row['in_progress_projects'],
            todoProjects: (int) $row['todo_projects'],
        );
    }
}
