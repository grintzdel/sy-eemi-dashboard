<?php

declare(strict_types=1);

namespace App\Modules\User\Infrastructure\Services;

use App\Modules\User\Domain\Exceptions\EmailAlreadyExistsException;
use App\Modules\User\Domain\ValueObjects\UserEmail;
use App\Modules\User\Infrastructure\Doctrine\Entities\UserEntity;
use Doctrine\ORM\EntityManagerInterface;

final readonly class EmailUniquenessService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * @throws EmailAlreadyExistsException
     */
    public function ensureEmailIsUnique(UserEmail $email, ?string $excludeUserId = null): void
    {
        $qb = $this->entityManager
            ->getRepository(UserEntity::class)
            ->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.email = :email')
            ->andWhere('u.deletedAt IS NULL')
            ->setParameter('email', $email->getValue());

        if ($excludeUserId !== null) {
            $qb->andWhere('u.id != :userId')
                ->setParameter('userId', $excludeUserId);
        }

        $count = (int) $qb->getQuery()->getSingleScalarResult();

        if ($count > 0) {
            throw EmailAlreadyExistsException::withEmail($email->getValue());
        }
    }
}
