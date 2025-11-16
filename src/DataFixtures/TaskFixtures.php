<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Modules\Employee\Infrastructure\Doctrine\Entities\EmployeeEntity;
use App\Modules\Project\Infrastructure\Doctrine\Entities\ProjectEntity;
use App\Modules\Shared\Domain\Enums\Status;
use App\Modules\Task\Infrastructure\Doctrine\Entities\TaskEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Uid\Uuid;

final class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function getDependencies(): array
    {
        return [
            EmployeeFixtures::class,
            ProjectFixtures::class,
        ];
    }

    /**
     * @throws \ReflectionException
     */
    public function load(ObjectManager $manager): void
    {
        $taskTemplates = [
            ['Analyse des besoins', 'Recueillir et analyser les besoins utilisateurs'],
            ['Design UI/UX', 'Créer les maquettes et wireframes'],
            ['Développement frontend', 'Implémenter l\'interface utilisateur'],
            ['Développement backend', 'Créer les API et la logique métier'],
            ['Tests unitaires', 'Écrire et exécuter les tests unitaires'],
            ['Tests d\'intégration', 'Valider l\'intégration des composants'],
            ['Documentation technique', 'Rédiger la documentation du code'],
            ['Revue de code', 'Examiner et valider le code produit'],
            ['Déploiement', 'Mettre en production la nouvelle version'],
            ['Formation utilisateurs', 'Former les utilisateurs finaux']
        ];

        $employees = [];
        for ($i = 1; $i <= 20; $i++)
        {
            $employees[] = $this->getReference('employee_' . $i, EmployeeEntity::class);
        }

        $this->createTasksForYear($manager, $taskTemplates, $employees, 2024, 12, 2, 4, 5, 15);

        $this->createTasksForYear($manager, $taskTemplates, $employees, 2025, 11, 1, 3, 5, 12);

        for ($i = 1; $i <= 10; $i++)
        {
            $project = $this->getReference('project_2024_' . rand(1, 12) . '_0', ProjectEntity::class);
            $template = $taskTemplates[array_rand($taskTemplates)];
            $createdAt = $project->getCreatedAt();

            $deletedTask = new TaskEntity(
                id: Uuid::v4()->toString(),
                name: '[DELETED] ' . $template[0],
                description: $template[1],
                projectId: $project->getId(),
                status: Status::TODO->value,
                assignedTo: [],
                createdAt: $createdAt,
                updatedAt: $createdAt
            );

            $reflection = new \ReflectionClass($deletedTask);
            $property = $reflection->getProperty('deletedAt');
            $property->setValue($deletedTask, new \DateTimeImmutable());

            $manager->persist($deletedTask);
        }

        $manager->flush();
    }

    private function getRandomEmployees(array $employees, int $count): array
    {
        shuffle($employees);
        return array_slice($employees, 0, $count);
    }

    /**
     * @throws \ReflectionException
     */
    private function createTasksForYear(
        ObjectManager $manager,
        array $taskTemplates,
        array $employees,
        int $year,
        int $maxMonth,
        int $minProjects,
        int $maxProjects,
        int $minTasks,
        int $maxTasks
    ): void
    {
        for ($month = 1; $month <= $maxMonth; $month++)
        {
            $projectsThisMonth = rand($minProjects, $maxProjects);

            for ($p = 0; $p < $projectsThisMonth; $p++)
            {
                $projectRef = "project_{$year}_{$month}_{$p}";

                if (!$this->hasReference($projectRef, ProjectEntity::class)) {
                    continue;
                }

                $project = $this->getReference($projectRef, ProjectEntity::class);
                $tasksCount = rand($minTasks, $maxTasks);
                $taskIds = [];

                for ($t = 0; $t < $tasksCount; $t++)
                {
                    $template = $taskTemplates[array_rand($taskTemplates)];
                    $assignedEmployees = $this->getRandomEmployees($employees, rand(1, 3));
                    $assignedTo = array_map(fn($emp) => $emp->getId(), $assignedEmployees);
                    $status = $this->determineStatus($t, $tasksCount);
                    $createdAt = $project->getCreatedAt();

                    $task = new TaskEntity(
                        id: Uuid::v4()->toString(),
                        name: $template[0] . ' #' . ($t + 1),
                        description: $template[1] . '. ' . $this->faker->sentence(),
                        projectId: $project->getId(),
                        status: $status->value,
                        assignedTo: $assignedTo,
                        createdAt: $createdAt,
                        updatedAt: $createdAt
                    );

                    $manager->persist($task);
                    $taskIds[] = $task->getId();

                    $this->updateEmployeeTaskIds($assignedEmployees, $task->getId());
                }

                $this->updateProjectTaskIds($project, $taskIds);
            }
        }
    }

    /**
     * @throws \ReflectionException
     */
    private function updateEmployeeTaskIds(array $employees, string $taskId): void
    {
        foreach ($employees as $employee)
        {
            $currentTaskIds = $employee->getTaskIds();
            $currentTaskIds[] = $taskId;

            $reflection = new \ReflectionClass($employee);
            $property = $reflection->getProperty('taskIds');
            $property->setValue($employee, $currentTaskIds);
        }
    }

    /**
     * @throws \ReflectionException
     */
    private function updateProjectTaskIds(ProjectEntity $project, array $taskIds): void
    {
        $reflection = new \ReflectionClass($project);
        $property = $reflection->getProperty('taskIds');
        $property->setValue($project, $taskIds);
    }

    private function determineStatus(int $taskIndex, int $totalTasks): Status
    {
        $progress = ($taskIndex / $totalTasks) * 100;

        if ($progress < 40) {
            return Status::DONE;
        } elseif ($progress < 70) {
            return Status::ON_GOING;
        } else {
            return Status::TODO;
        }
    }
}
