<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Modules\Employee\Infrastructure\Doctrine\Entities\EmployeeEntity;
use App\Modules\Shared\Domain\Enums\EmployeeStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Uid\Uuid;

final class EmployeeFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function load(ObjectManager $manager): void
    {
        $positions = [
            'Développeur Full Stack',
            'Développeur Frontend',
            'Développeur Backend',
            'DevOps Engineer',
            'Chef de Projet',
            'Product Owner',
            'Scrum Master',
            'Designer UI/UX',
            'Data Analyst',
            'QA Engineer',
        ];

        for ($i = 1; $i <= 20; ++$i) {
            $createdAt = $this->getRandomDateInLast12Months();
            $status = $this->faker->boolean(80) ? EmployeeStatus::ACTIVE : EmployeeStatus::INACTIVE;
            $avatarId = $this->faker->numberBetween(1, 200);

            $employee = new EmployeeEntity(
                id: Uuid::v4()->toString(),
                firstName: $this->faker->firstName(),
                lastName: $this->faker->lastName(),
                email: $this->faker->unique()->safeEmail(),
                position: $positions[array_rand($positions)],
                avatar: "https://picsum.photos/seed/{$avatarId}/200/200",
                taskIds: [],
                status: $status,
                createdAt: $createdAt,
                updatedAt: $createdAt,
            );

            $manager->persist($employee);
            $this->addReference('employee_'.$i, $employee);
        }

        for ($i = 1; $i <= 3; ++$i) {
            $createdAt = $this->getRandomDateInLast12Months();
            $avatarId = $this->faker->numberBetween(201, 300);

            $deletedEmployee = new EmployeeEntity(
                id: Uuid::v4()->toString(),
                firstName: $this->faker->firstName(),
                lastName: $this->faker->lastName(),
                email: $this->faker->unique()->safeEmail(),
                position: $positions[array_rand($positions)],
                avatar: "https://picsum.photos/seed/{$avatarId}/200/200",
                taskIds: [],
                status: EmployeeStatus::INACTIVE,
                createdAt: $createdAt,
                updatedAt: $createdAt,
            );

            $reflection = new \ReflectionClass($deletedEmployee);
            $property = $reflection->getProperty('deletedAt');
            $property->setValue($deletedEmployee, new \DateTimeImmutable());

            $manager->persist($deletedEmployee);
        }

        $manager->flush();
    }

    /**
     * @throws \DateMalformedStringException
     */
    private function getRandomDateInLast12Months(): \DateTimeImmutable
    {
        $now = new \DateTimeImmutable();
        $monthsAgo = rand(1, 12);

        return $now->modify("-{$monthsAgo} months");
    }
}
