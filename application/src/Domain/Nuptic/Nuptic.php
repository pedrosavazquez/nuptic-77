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

        /** @var NupticWasCreatedRepresentation $representation */
        $representation = $this->representedAs(new NupticWasCreatedRepresentation());
        $this->events[] = NupticWasCreated::fromNuptic($representation);
    }

    public function getEvents(): array
    {
        return $this->events;
    }

    public function representedAs(NupticRepresentation $representation): NupticRepresentation
    {
        return $representation->setNupticId($this->nupticId)
                ->setSimulatorId($this->simulatorId)
                ->setNum($this->num)
                ->setDirection($this->direction)
                ->setRoute($this->route)
                ->setCreatedAt($this->createdAt);
    }
}
