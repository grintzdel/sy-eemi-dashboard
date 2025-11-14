<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Presentation\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Modules\Authentication\Application\Commands\Login\LoginCommand;
use App\Modules\Authentication\Application\DTO\LoginDTO;
use App\Modules\Authentication\Application\ViewModels\TokenViewModel;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class LoginProcessor implements ProcessorInterface
{
    use HandleTrait;

    public function __construct(
        MessageBusInterface $messageBus
    )
    {
        $this->messageBus = $messageBus;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): TokenViewModel
    {
        assert($data instanceof LoginDTO);

        $command = new LoginCommand(
            email: $data->email,
            password: $data->password
        );

        return $this->handle($command);
    }
}
