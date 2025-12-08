<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Presentation\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Modules\Analytics\Application\Queries\GetProjectsCompletedByPeriod\GetProjectsCompletedByPeriodQuery;
use App\Modules\Analytics\Domain\ValueObjects\PeriodType;
use App\Modules\Analytics\Presentation\ApiResource\ProjectsCompletedResource;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class GetProjectsCompletedByPeriodProvider implements ProviderInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ProjectsCompletedResource
    {
        $request = $context['request'] ?? null;

        $periodValue = $request?->query->get('period', 'month');
        $year = $request?->query->get('year');

        $period = PeriodType::fromString($periodValue);
        $yearInt = null !== $year ? (int) $year : null;

        $query = new GetProjectsCompletedByPeriodQuery(
            period: $period,
            year: $yearInt
        );

        $viewModel = $this->handle($query);

        return new ProjectsCompletedResource(data: $viewModel);
    }
}
