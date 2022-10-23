<?php

declare(strict_types=1);

namespace App\Application\Nuptic\Command;

use App\Application\Shared\Bus\Command\CommandHandler;
use App\Application\Shared\Bus\Event\EventBus;
use App\Domain\Nuptic\Direction;
use App\Domain\Nuptic\Num;
use App\Domain\Nuptic\Nuptic;
use App\Domain\Nuptic\NupticId;
use App\Domain\Nuptic\NupticWriteRepository;
use App\Domain\Nuptic\Route;
use App\Domain\Nuptic\SimulatorId;

final class RegisterNupticCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly NupticWriteRepository $nupticRepository,
        private readonly EventBus $eventBus
        )
    {

    }

    public function __invoke(RegisterNupticCommand $command): void
    {

        $nuptic = new Nuptic(
            NupticId::fromString($command->id),
            SimulatorId::fromString($command->simulatorId),
            Num::fromInt($command->num),
            Direction::fromString($command->direction),
            Route::fromInt($command->route),
        );
        $this->nupticRepository->save($nuptic);

        $this->eventBus->handleBatch($nuptic->events);
    }
}
