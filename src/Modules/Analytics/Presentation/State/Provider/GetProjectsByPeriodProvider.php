<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Presentation\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Modules\Analytics\Application\Queries\GetProjectsByPeriod\GetProjectsByPeriodQuery;
use App\Modules\Analytics\Domain\ValueObjects\PeriodType;
use App\Modules\Analytics\Presentation\ApiResource\ProjectsByPeriodResource;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class GetProjectsByPeriodProvider implements ProviderInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ProjectsByPeriodResource
    {
        $request = $context['request'] ?? null;

        $periodValue = $request?->query->get('period', 'month');
        $year = $request?->query->get('year');

        $period = PeriodType::fromString($periodValue);
        $yearInt = $year !== null ? (int) $year : null;

        $query = new GetProjectsByPeriodQuery(
            period: $period,
            year: $yearInt
        );

        $viewModels = $this->handle($query);

        return new ProjectsByPeriodResource(data: $viewModels);
    }
}
