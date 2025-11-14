<?php

declare(strict_types=1);

namespace App\Modules\User\Infrastructure\Doctrine\Repositories;

use App\Modules\Shared\Domain\Enums\Roles;
use App\Modules\Shared\Domain\ValueObjects\UserId;
use App\Modules\User\Domain\Entities\User;
use App\Modules\User\Domain\Repositories\IUserRepository;
use App\Modules\User\Domain\ValueObjects\HashedPassword;
use App\Modules\User\Domain\ValueObjects\UserEmail;
use App\Modules\User\Domain\ValueObjects\UserName;
use App\Modules\User\Infrastructure\Doctrine\Entities\UserEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserEntity>
 */
class SqlUserRepository extends ServiceEntityRepository implements IUserRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserEntity::class);
    }

    public function findById(UserId $id): ?User
    {
        $entity = $this->findOneBy([
            'id' => $id->getValue(),
            'deletedAt' => null
        ]);

        return $entity ? $this->toDomain($entity) : null;
    }

    public function findByEmail(UserEmail $email): ?User
    {
        $entity = $this->findOneBy([
            'email' => $email->getValue(),
            'deletedAt' => null
        ]);

        return $entity ? $this->toDomain($entity) : null;
    }

    public function findAll(): array
    {
        $entities = $this->createQueryBuilder('u')
            ->where('u.deletedAt IS NULL')
            ->orderBy('u.createdAt', 'DESC')
            ->getQuery()
            ->getResult();

        return array_map(
            fn(UserEntity $entity) => $this->toDomain($entity),
            $entities
        );
    }

    public function save(User $user): void
    {
        $entity = $this->find($user->getId()->getValue());

        if ($entity === null) {
            $entity = $this->toInfrastructure($user);
            $this->getEntityManager()->persist($entity);
        } else {
            $this->updateEntity($entity, $user);
        }

        $this->getEntityManager()->flush();
    }

    public function delete(UserId $id): void
    {
        $entity = $this->find($id->getValue());

        if ($entity !== null) {
            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush();
        }
    }

    private function toDomain(UserEntity $entity): User
    {
        return User::reconstitute(
            id: new UserId($entity->getId()),
            name: new UserName($entity->getName()),
            email: new UserEmail($entity->getEmail()),
            password: new HashedPassword($entity->getPassword()),
            role: Roles::from($entity->getRole()),
            createdAt: $entity->getCreatedAt(),
            updatedAt: $entity->getUpdatedAt(),
            deletedAt: $entity->getDeletedAt()
        );
    }

    private function toInfrastructure(User $user): UserEntity
    {
        return new UserEntity(
            id: $user->getId()->getValue(),
            name: $user->getName()->getValue(),
            email: $user->getEmail()->getValue(),
            password: $user->getPassword()->getValue(),
            role: $user->getRole()->value,
            createdAt: $user->getCreatedAt(),
            updatedAt: $user->getUpdatedAt(),
            deletedAt: $user->getDeletedAt()
        );
    }

    private function updateEntity(UserEntity $entity, User $user): void
    {
        $entity->setName($user->getName()->getValue());
        $entity->setEmail($user->getEmail()->getValue());
        $entity->setPassword($user->getPassword()->getValue());
        $entity->setRole($user->getRole()->value);
        $entity->setUpdatedAt($user->getUpdatedAt());
        $entity->setDeletedAt($user->getDeletedAt());
    }
}
