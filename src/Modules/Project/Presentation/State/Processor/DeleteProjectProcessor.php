<?php

declare(strict_types=1);

namespace App\Modules\Project\Presentation\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Modules\Project\Application\Commands\DeleteProject\DeleteProjectCommand;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class DeleteProjectProcessor implements ProcessorInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        $command = new DeleteProjectCommand($uriVariables['id']);

        $this->handle($command);
    }
}
