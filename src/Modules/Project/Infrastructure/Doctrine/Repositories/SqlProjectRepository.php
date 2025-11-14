<?php

declare(strict_types=1);

namespace App\Modules\Project\Infrastructure\Doctrine\Repositories;

use App\Modules\Project\Domain\Entities\Project;
use App\Modules\Project\Domain\Repositories\IProjectRepository;
use App\Modules\Project\Infrastructure\Doctrine\Entities\ProjectEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SqlProjectRepository extends ServiceEntityRepository implements IProjectRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectEntity::class);
    }

    public function findById(string $id): ?Project
    {
        /** @var ProjectEntity|null $projectEntity */
        $projectEntity = $this->find($id);

        return $projectEntity ? $this->toDomain($projectEntity) : null;
    }

    public function findAll(): array
    {
        /** @var array<ProjectEntity> $projectEntities */
        $projectEntities = $this->findBy(['deletedAt' => null]);

        return array_map(fn(ProjectEntity $entity) => $this->toDomain($entity), $projectEntities);
    }

    public function save(Project $project): void
    {
        $em = $this->getEntityManager();

        /** @var ProjectEntity|null $projectEntity */
        $projectEntity = $this->find($project->getId());

        if ($projectEntity === null) {
            $projectEntity = new ProjectEntity(
                $project->getId(),
                $project->getName(),
                $project->getDescription(),
                $project->getTaskIds(),
                $project->getCreatedAt(),
                $project->getUpdatedAt(),
                $project->getDeletedAt()
            );
            $em->persist($projectEntity);
        } else {
            $projectEntity->setName($project->getName());
            $projectEntity->setDescription($project->getDescription());
            $projectEntity->setTaskIds($project->getTaskIds());
            $projectEntity->setUpdatedAt($project->getUpdatedAt());
            $projectEntity->setDeletedAt($project->getDeletedAt());
        }

        $em->flush();
    }

    public function delete(Project $project): void
    {
        $project->delete();
        $this->save($project);
    }

    private function toDomain(ProjectEntity $entity): Project
    {
        $project = new Project(
            $entity->getId(),
            $entity->getName(),
            $entity->getDescription(),
            $entity->getTaskIds(),
            $entity->getCreatedAt()
        );

        $reflection = new \ReflectionClass($project);

        $updatedAtProperty = $reflection->getProperty('updatedAt');
        $updatedAtProperty->setValue($project, $entity->getUpdatedAt());

        $deletedAtProperty = $reflection->getProperty('deletedAt');
        $deletedAtProperty->setValue($project, $entity->getDeletedAt());

        return $project;
    }
}
