<?php

declare(strict_types=1);

namespace App\Modules\Project\Presentation\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Modules\Project\Application\Commands\UpdateProject\UpdateProjectCommand;
use App\Modules\Project\Application\Queries\GetProject\GetProjectQuery;
use App\Modules\Project\Presentation\ApiResource\ProjectResource;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class UpdateProjectProcessor implements ProcessorInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ProjectResource
    {
        $command = new UpdateProjectCommand(
            $uriVariables['id'],
            $data->data->name,
            $data->data->description
        );

        $this->handle($command);

        $viewModel = $this->handle(new GetProjectQuery($uriVariables['id']));

        return new ProjectResource($viewModel);
    }
}
