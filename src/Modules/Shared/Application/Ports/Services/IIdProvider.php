<?php

declare(strict_types=1);

namespace App\Modules\Shared\Application\Ports\Services;

interface IIdProvider
{
    public function generateId(): string;
}