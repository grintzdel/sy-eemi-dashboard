<?php

declare(strict_types=1);

namespace App\Modules\Task\Presentation\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Modules\Task\Application\ViewModels\TaskViewModel;
use App\Modules\Task\Presentation\State\Processor\CreateTaskProcessor;
use App\Modules\Task\Presentation\State\Processor\DeleteTaskProcessor;
use App\Modules\Task\Presentation\State\Processor\UpdateTaskProcessor;
use App\Modules\Task\Presentation\State\Provider\GetTaskProvider;
use App\Modules\Task\Presentation\State\Provider\ListTasksProvider;

#[ApiResource(
    shortName: 'Task',
    operations: [
        new GetCollection(
            provider: ListTasksProvider::class
        ),
        new Get(
            provider: GetTaskProvider::class
        ),
        new Post(
            processor: CreateTaskProcessor::class
        ),
        new Put(
            processor: UpdateTaskProcessor::class
        ),
        new Delete(
            processor: DeleteTaskProcessor::class
        )
    ],
    paginationEnabled: false
)]
final readonly class TaskResource
{
    public function __construct(
        public TaskViewModel $data
    ) {}
}
