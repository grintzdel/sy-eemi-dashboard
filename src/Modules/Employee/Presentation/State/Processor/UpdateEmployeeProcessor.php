<?php

declare(strict_types=1);

namespace App\Modules\Employee\Presentation\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Modules\Employee\Application\Commands\UpdateEmployee\UpdateEmployeeCommand;
use App\Modules\Employee\Application\Queries\GetEmployee\GetEmployeeQuery;
use App\Modules\Employee\Presentation\ApiResource\EmployeeResource;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class UpdateEmployeeProcessor implements ProcessorInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): EmployeeResource
    {
        $command = new UpdateEmployeeCommand(
            $uriVariables['id'],
            $data->data->firstName,
            $data->data->lastName,
            $data->data->email,
            $data->data->position
        );

        $this->handle($command);

        $viewModel = $this->handle(new GetEmployeeQuery($uriVariables['id']));

        return new EmployeeResource($viewModel);
    }
}
