<?php

declare(strict_types=1);


use App\Modules\Shared\Domain\Enums\Roles;
use App\Modules\User\Infrastructure\Doctrine\Entities\UserEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserFixtures extends Fixture
{
    private Generator $faker;

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        $admin = $this->createUser('Admin User', 'admin@example.com', Roles::ROLE_ADMIN);
        $manager->persist($admin);
        $this->addReference('user_admin', $admin);

        for ($i = 1; $i <= 2; $i++) {
            $moderator = $this->createUser(
                $this->faker->name(),
                "moderator$i@example.com",
                Roles::ROLE_MODERATOR
            );
            $manager->persist($moderator);
            $this->addReference('user_moderator_' . $i, $moderator);
        }


        for ($i = 1; $i <= 17; $i++) {
            $user = $this->createUser(
                $this->faker->name(),
                $this->faker->unique()->safeEmail(),
                Roles::ROLE_USER
            );
            $manager->persist($user);
            $this->addReference('user_regular_' . $i, $user);
        }

        $manager->flush();
    }

    private function createUser(string $name, string $email, Roles $role): UserEntity
    {
        $tempUser = new UserEntity(
            id: $this->faker->uuid(),
            name: $name,
            email: $email,
            password: 'temp',
            role: $role->value,
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable()
        );

        $hashedPassword = $this->passwordHasher->hashPassword($tempUser, 'password');

        return new UserEntity(
            id: $tempUser->getId(),
            name: $name,
            email: $email,
            password: $hashedPassword,
            role: $role->value,
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable()
        );
    }
}
