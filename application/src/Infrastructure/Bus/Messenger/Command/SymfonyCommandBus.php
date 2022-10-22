<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus\Messenger\Command;

use App\Application\Shared\Bus\Command\Command;
use App\Application\Shared\Bus\Command\CommandBus;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

final class SymfonyCommandBus implements CommandBus
{
    public function __construct(private readonly MessageBusInterface $commandBus)
    {
    }

    public function execute(Command $command): Envelope
    {
        try {
            $envelope = $this->commandBus->dispatch($command);
        } catch (HandlerFailedException $exception) {
            throw $exception->getPrevious();
        }

        return $envelope;
    }
}
