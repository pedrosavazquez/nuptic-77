<?php

declare(strict_types=1);

namespace App\Domain\Nuptic;

final class Nuptic
{
    /**
    * @var NupticWasCreated[]
     */
    public readonly array $events;

    public function __construct(
        private readonly NupticId $nupticId,
        private readonly SimulatorId $simulatorId,
        private readonly Num $num,
        private readonly Direction $direction,
        private readonly Route $route
    ) {
        $this->events[] = NupticWasCreated::fromNuptic($this);
    }
}
