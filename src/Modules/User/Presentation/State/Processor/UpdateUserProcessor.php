<?php

declare(strict_types=1);

namespace App\Modules\User\Presentation\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Modules\User\Application\Commands\UpdateUser\UpdateUserCommand;
use App\Modules\User\Presentation\ApiResource\UserResource;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class UpdateUserProcessor implements ProcessorInterface
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
        $command = new UpdateUserCommand(
            id: $uriVariables['id'],
            name: $data['name'],
            email: $data['email']
        );

        $viewModel = $this->handle($command);

        return new UserResource($viewModel);
    }
}
