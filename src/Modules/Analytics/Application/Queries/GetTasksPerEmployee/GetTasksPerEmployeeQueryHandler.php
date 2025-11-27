<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Application\Queries\GetTasksPerEmployee;

use App\Modules\Analytics\Application\ViewModels\TasksPerEmployeeViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetTasksPerEmployeeQueryHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function __invoke(GetTasksPerEmployeeQuery $query): array
    {
        $sql = <<<SQL
            SELECT
                e.id as employee_id,
                e.first_name,
                e.last_name,
                e.email,
                COUNT(t.id) as total_tasks,
                SUM(CASE WHEN t.status = 'DONE' THEN 1 ELSE 0 END) as completed_tasks,
                SUM(CASE WHEN t.status = 'ON_GOING' THEN 1 ELSE 0 END) as in_progress_tasks,
                SUM(CASE WHEN t.status = 'TODO' THEN 1 ELSE 0 END) as todo_tasks
            FROM employees e
            INNER JOIN tasks t ON JSON_CONTAINS(t.assigned_to, JSON_QUOTE(e.id))
            WHERE e.deleted_at IS NULL
                AND t.deleted_at IS NULL
            GROUP BY e.id, e.first_name, e.last_name, e.email
            ORDER BY completed_tasks DESC, total_tasks DESC
        SQL;

        $stmt = $this->entityManager->getConnection()->prepare($sql);
        $result = $stmt->executeQuery();

        return array_map(function (array $row): TasksPerEmployeeViewModel {
            $totalTasks = (int) $row["total_tasks"];
            $completedTasks = (int) $row["completed_tasks"];
            $completionRate =
                $totalTasks > 0
                    ? round(($completedTasks / $totalTasks) * 100, 1)
                    : 0.0;

            return new TasksPerEmployeeViewModel(
                employeeId: $row["employee_id"],
                firstName: $row["first_name"],
                lastName: $row["last_name"],
                email: $row["email"],
                totalTasks: $totalTasks,
                completedTasks: $completedTasks,
                inProgressTasks: (int) $row["in_progress_tasks"],
                todoTasks: (int) $row["todo_tasks"],
                completionRate: $completionRate,
            );
        }, $result->fetchAllAssociative());
    }
}
