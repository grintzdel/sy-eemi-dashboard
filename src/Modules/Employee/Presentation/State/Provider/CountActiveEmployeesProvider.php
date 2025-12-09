<?php

declare(strict_types=1);

namespace App\Modules\Employee\Presentation\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Modules\Employee\Application\Queries\CountActiveEmployees\CountActiveEmployeesQuery;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class CountActiveEmployeesProvider implements ProviderInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function provide(
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): array {
        $count = $this->handle(new CountActiveEmployeesQuery());

        return ['count' => $count];
    }
}
