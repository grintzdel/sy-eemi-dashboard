<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Modules\Project\Infrastructure\Doctrine\Entities\ProjectEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Uid\Uuid;

final class ProjectFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create("fr_FR");
    }

    public function load(ObjectManager $manager): void
    {
        $projectTemplates = [
            [
                "Refonte du site web",
                "Modernisation complète du site institutionnel",
            ],
            [
                "Application mobile",
                'Développement d\'une application mobile native',
            ],
            ["Migration cloud", 'Migration de l\'infrastructure vers AWS'],
            [
                "Tableau de bord analytics",
                'Création d\'un dashboard de métriques',
            ],
            ["API REST v2", 'Refonte de l\'API avec GraphQL'],
            ["Système de paiement", "Intégration de Stripe et PayPal"],
            [
                "Module de facturation",
                "Automatisation de la facturation client",
            ],
            [
                "Plateforme e-learning",
                'Création d\'une plateforme de formation en ligne',
            ],
            ["CRM interne", 'Développement d\'un CRM personnalisé'],
            ["Système de tickets", "Gestion des demandes support"],
        ];

        for ($month = 1; $month <= 12; $month++) {
            $projectsThisMonth = rand(2, 4);

            for ($p = 0; $p < $projectsThisMonth; $p++) {
                $template = $projectTemplates[array_rand($projectTemplates)];
                $createdAt = $this->getRandomDateInMonth(2024, $month);

                $project = new ProjectEntity(
                    id: Uuid::v4()->toString(),
                    name: $template[0] . " " . $this->faker->company(),
                    description: $template[1] . ". " . $this->faker->sentence(),
                    taskIds: [],
                    createdAt: $createdAt,
                    updatedAt: $createdAt,
                );

                $manager->persist($project);
                $this->addReference(
                    "project_2024_" . $month . "_" . $p,
                    $project,
                );
            }
        }

        for ($month = 1; $month <= 11; $month++) {
            $projectsThisMonth = rand(1, 3);

            for ($p = 0; $p < $projectsThisMonth; $p++) {
                $template = $projectTemplates[array_rand($projectTemplates)];
                $createdAt = $this->getRandomDateInMonth(2025, $month);

                $project = new ProjectEntity(
                    id: Uuid::v4()->toString(),
                    name: $template[0] . " " . $this->faker->company(),
                    description: $template[1] . ". " . $this->faker->sentence(),
                    taskIds: [],
                    createdAt: $createdAt,
                    updatedAt: $createdAt,
                );

                $manager->persist($project);
                $this->addReference(
                    "project_2025_" . $month . "_" . $p,
                    $project,
                );
            }
        }

        for ($i = 1; $i <= 5; $i++) {
            $template = $projectTemplates[array_rand($projectTemplates)];
            $createdAt = $this->getRandomDateInMonth(2024, rand(1, 12));

            $deletedProject = new ProjectEntity(
                id: Uuid::v4()->toString(),
                name: "[ARCHIVED] " . $template[0],
                description: $template[1],
                taskIds: [],
                createdAt: $createdAt,
                updatedAt: $createdAt,
            );

            $reflection = new \ReflectionClass($deletedProject);
            $property = $reflection->getProperty("deletedAt");
            $property->setValue($deletedProject, new \DateTimeImmutable());

            $manager->persist($deletedProject);
        }

        $manager->flush();
    }

    private function getRandomDateInMonth(
        int $year,
        int $month,
    ): \DateTimeImmutable {
        $day = rand(1, 28);
        $hour = rand(8, 18);
        $minute = rand(0, 59);

        return new \DateTimeImmutable(
            "{$year}-{$month}-{$day} {$hour}:{$minute}:00",
        );
    }
}
