<?php

declare(strict_types=1);

namespace App\Modules\Employee\Presentation\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Modules\Employee\Application\Commands\CreateEmployee\CreateEmployeeCommand;
use App\Modules\Employee\Application\Queries\GetEmployee\GetEmployeeQuery;
use App\Modules\Employee\Presentation\ApiResource\EmployeeResource;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class CreateEmployeeProcessor implements ProcessorInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): EmployeeResource
    {
        $command = new CreateEmployeeCommand(
            $data->data->firstName,
            $data->data->lastName,
            $data->data->email,
            $data->data->position,
            $data->data->taskIds ?? []
        );

        $employeeId = $this->handle($command);

        $viewModel = $this->handle(new GetEmployeeQuery($employeeId));

        return new EmployeeResource($viewModel);
    }
}
