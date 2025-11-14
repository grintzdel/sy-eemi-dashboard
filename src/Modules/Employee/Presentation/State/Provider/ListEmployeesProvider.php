<?php

declare(strict_types=1);

namespace App\Modules\Employee\Presentation\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Modules\Employee\Application\Queries\ListEmployees\ListEmployeesQuery;
use App\Modules\Employee\Presentation\ApiResource\EmployeeResource;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class ListEmployeesProvider implements ProviderInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $viewModels = $this->handle(new ListEmployeesQuery());

        return array_map(
            fn($viewModel) => new EmployeeResource($viewModel),
            $viewModels
        );
    }
}
