<?php

declare(strict_types=1);

namespace App\Modules\Employee\Infrastructure\Doctrine\Repositories;

use App\Modules\Employee\Domain\Entities\Employee;
use App\Modules\Employee\Domain\Repositories\IEmployeeRepository;
use App\Modules\Employee\Infrastructure\Doctrine\Entities\EmployeeEntity;
use Doctrine\ORM\EntityManagerInterface;

final readonly class SqlEmployeeRepository implements IEmployeeRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function findById(string $id): ?Employee
    {
        $entity = $this->entityManager
            ->getRepository(EmployeeEntity::class)
            ->findOneBy(['id' => $id, 'deletedAt' => null]);

        return $entity ? $this->toDomain($entity) : null;
    }

    public function findAll(): array
    {
        $entities = $this->entityManager
            ->getRepository(EmployeeEntity::class)
            ->findBy(['deletedAt' => null]);

        return array_map(fn(EmployeeEntity $entity) => $this->toDomain($entity), $entities);
    }

    public function save(Employee $employee): void
    {
        $entity = $this->entityManager
            ->getRepository(EmployeeEntity::class)
            ->find($employee->getId());

        if ($entity === null) {
            $entity = new EmployeeEntity(
                $employee->getId(),
                $employee->getFirstName(),
                $employee->getLastName(),
                $employee->getEmail(),
                $employee->getPosition(),
                $employee->getTaskIds(),
                $employee->getCreatedAt(),
                $employee->getUpdatedAt()
            );
            $this->entityManager->persist($entity);
        } else {
            $entity->setFirstName($employee->getFirstName());
            $entity->setLastName($employee->getLastName());
            $entity->setEmail($employee->getEmail());
            $entity->setPosition($employee->getPosition());
            $entity->setTaskIds($employee->getTaskIds());
            $entity->setUpdatedAt($employee->getUpdatedAt());
        }

        $this->entityManager->flush();
    }

    public function delete(Employee $employee): void
    {
        $entity = $this->entityManager
            ->getRepository(EmployeeEntity::class)
            ->find($employee->getId());

        if ($entity !== null) {
            $entity->setDeletedAt($employee->getDeletedAt());
            $this->entityManager->flush();
        }
    }

    private function toDomain(EmployeeEntity $entity): Employee
    {
        $employee = new Employee(
            $entity->getId(),
            $entity->getFirstName(),
            $entity->getLastName(),
            $entity->getEmail(),
            $entity->getPosition(),
            $entity->getTaskIds(),
            $entity->getCreatedAt()
        );

        $reflection = new \ReflectionClass($employee);

        $updatedAtProperty = $reflection->getProperty('updatedAt');
        $updatedAtProperty->setValue($employee, $entity->getUpdatedAt());

        $deletedAtProperty = $reflection->getProperty('deletedAt');
        $deletedAtProperty->setValue($employee, $entity->getDeletedAt());

        return $employee;
    }
}
