<?php

declare(strict_types=1);

namespace App\Modules\Employee\Presentation\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Modules\Employee\Application\Queries\GetEmployee\GetEmployeeQuery;
use App\Modules\Employee\Presentation\ApiResource\EmployeeResource;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class GetEmployeeProvider implements ProviderInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): EmployeeResource
    {
        $viewModel = $this->handle(new GetEmployeeQuery($uriVariables['id']));

        return new EmployeeResource($viewModel);
    }
}
