<?php

declare(strict_types=1);

namespace App\Modules\Employee\Presentation\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Modules\Employee\Application\Commands\DeleteEmployee\DeleteEmployeeCommand;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class DeleteEmployeeProcessor implements ProcessorInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        $command = new DeleteEmployeeCommand($uriVariables['id']);

        $this->handle($command);
    }
}
