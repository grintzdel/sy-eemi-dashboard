<?php

declare(strict_types=1);

namespace App\Modules\User\Presentation\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Modules\Shared\Domain\Enums\Roles;
use App\Modules\User\Application\Commands\CreateUser\CreateUserCommand;
use App\Modules\User\Presentation\ApiResource\UserResource;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class CreateUserProcessor implements ProcessorInterface
{
    use HandleTrait;

    public function __construct(
        MessageBusInterface $messageBus
    )
    {
        $this->messageBus = $messageBus;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): UserResource
    {
        $command = new CreateUserCommand(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            role: $data['role'] ?? Roles::ROLE_USER->value
        );

        $viewModel = $this->handle($command);

        return new UserResource($viewModel);
    }
}
