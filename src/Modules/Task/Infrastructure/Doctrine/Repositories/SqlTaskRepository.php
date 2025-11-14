<?php

declare(strict_types=1);

namespace App\Modules\Task\Infrastructure\Doctrine\Repositories;

use App\Modules\Shared\Domain\Enums\Status;
use App\Modules\Task\Domain\Entities\Task;
use App\Modules\Task\Domain\Repositories\ITaskRepository;
use App\Modules\Task\Infrastructure\Doctrine\Entities\TaskEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SqlTaskRepository extends ServiceEntityRepository implements ITaskRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaskEntity::class);
    }

    public function findById(string $id): ?Task
    {
        /** @var TaskEntity|null $taskEntity */
        $taskEntity = $this->find($id);

        return $taskEntity ? $this->toDomain($taskEntity) : null;
    }

    public function findAll(): array
    {
        /** @var array<TaskEntity> $taskEntities */
        $taskEntities = $this->findBy(['deletedAt' => null]);

        return array_map(fn(TaskEntity $entity) => $this->toDomain($entity), $taskEntities);
    }

    public function findByProjectId(string $projectId): array
    {
        /** @var array<TaskEntity> $taskEntities */
        $taskEntities = $this->findBy([
            'projectId' => $projectId,
            'deletedAt' => null
        ]);

        return array_map(fn(TaskEntity $entity) => $this->toDomain($entity), $taskEntities);
    }

    public function findByEmployeeId(string $employeeId): array
    {
        $qb = $this->createQueryBuilder('t');
        $qb->where('JSON_CONTAINS(t.assignedTo, :employeeId) = 1')
           ->andWhere('t.deletedAt IS NULL')
           ->setParameter('employeeId', json_encode($employeeId));

        /** @var array<TaskEntity> $taskEntities */
        $taskEntities = $qb->getQuery()->getResult();

        return array_map(fn(TaskEntity $entity) => $this->toDomain($entity), $taskEntities);
    }

    public function save(Task $task): void
    {
        $em = $this->getEntityManager();

        /** @var TaskEntity|null $taskEntity */
        $taskEntity = $this->find($task->getId());

        if ($taskEntity === null) {
            $taskEntity = new TaskEntity(
                $task->getId(),
                $task->getName(),
                $task->getDescription(),
                $task->getProjectId(),
                $task->getStatus()->value,
                $task->getAssignedTo(),
                $task->getCreatedAt(),
                $task->getUpdatedAt(),
                $task->getDeletedAt()
            );
            $em->persist($taskEntity);
        } else {
            $taskEntity->setName($task->getName());
            $taskEntity->setDescription($task->getDescription());
            $taskEntity->setProjectId($task->getProjectId());
            $taskEntity->setStatus($task->getStatus()->value);
            $taskEntity->setAssignedTo($task->getAssignedTo());
            $taskEntity->setUpdatedAt($task->getUpdatedAt());
            $taskEntity->setDeletedAt($task->getDeletedAt());
        }

        $em->flush();
    }

    public function delete(Task $task): void
    {
        $task->delete();
        $this->save($task);
    }

    private function toDomain(TaskEntity $entity): Task
    {
        $task = new Task(
            $entity->getId(),
            $entity->getName(),
            $entity->getDescription(),
            $entity->getProjectId(),
            Status::from($entity->getStatus()),
            $entity->getAssignedTo(),
            $entity->getCreatedAt()
        );

        $reflection = new \ReflectionClass($task);

        $updatedAtProperty = $reflection->getProperty('updatedAt');
        $updatedAtProperty->setValue($task, $entity->getUpdatedAt());

        $deletedAtProperty = $reflection->getProperty('deletedAt');
        $deletedAtProperty->setValue($task, $entity->getDeletedAt());

        return $task;
    }
}
