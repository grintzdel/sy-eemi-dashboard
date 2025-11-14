<?php

declare(strict_types=1);

namespace App\Modules\Employee\Presentation\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Modules\Employee\Application\ViewModels\EmployeeViewModel;
use App\Modules\Employee\Presentation\State\Processor\CreateEmployeeProcessor;
use App\Modules\Employee\Presentation\State\Processor\DeleteEmployeeProcessor;
use App\Modules\Employee\Presentation\State\Processor\UpdateEmployeeProcessor;
use App\Modules\Employee\Presentation\State\Provider\GetEmployeeProvider;
use App\Modules\Employee\Presentation\State\Provider\ListEmployeesProvider;

#[ApiResource(
    shortName: 'Employee',
    operations: [
        new GetCollection(
            provider: ListEmployeesProvider::class
        ),
        new Get(
            provider: GetEmployeeProvider::class
        ),
        new Post(
            processor: CreateEmployeeProcessor::class
        ),
        new Put(
            processor: UpdateEmployeeProcessor::class
        ),
        new Delete(
            processor: DeleteEmployeeProcessor::class
        )
    ],
    paginationEnabled: false
)]
final readonly class EmployeeResource
{
    public function __construct(
        public EmployeeViewModel $data
    ) {}
}
