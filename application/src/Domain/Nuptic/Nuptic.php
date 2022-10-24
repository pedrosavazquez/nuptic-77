<?php

declare(strict_types=1);

namespace App\Domain\Nuptic;

use DateTimeImmutable;

final class Nuptic
{
    /**
     * @var NupticWasCreated[]
     */
    private array $events;
    private readonly Route $route;
    private readonly Direction $direction;
    private readonly Num $num;
    private readonly SimulatorId $simulatorId;
    private readonly NupticId $nupticId;
    private readonly DateTimeImmutable $createdAt;


    public function __construct(
        NupticId $nupticId,
        SimulatorId $simulatorId,
        Num $num,
        Direction $direction,
        Route $route,
    )
    {
        $this->nupticId = $nupticId;
        $this->simulatorId = $simulatorId;
        $this->num = $num;
        $this->direction = $direction;
        $this->route = $route;
        $this->createdAt = new DateTimeImmutable();

        $this->events[] = NupticWasCreated::fromNuptic($this);
    }

    public function getEvents(): array
    {
        return $this->events;
    }
}
