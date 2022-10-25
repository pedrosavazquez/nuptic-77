<?php

declare(strict_types=1);

namespace App\Domain\Nuptic;

use DateTimeImmutable;

abstract class NupticRepresentation
{
    protected ?Route $route;
    protected ?Direction $direction;
    protected ?Num $num;
    protected ?SimulatorId $simulatorId;
    protected ?NupticId $nupticId;
    protected ?DateTimeImmutable $createdAt;

    public function __construct()
    {}

    public function setRoute(Route $route): NupticRepresentation
    {
        $this->route = $route;
        return $this;
    }

    public function setDirection(Direction $direction): NupticRepresentation
    {
        $this->direction = $direction;
        return $this;
    }

    public function setNum(Num $num): NupticRepresentation
    {
        $this->num = $num;
        return $this;
    }

    public function setSimulatorId(SimulatorId $simulatorId): NupticRepresentation
    {
        $this->simulatorId = $simulatorId;
        return $this;
    }

    public function setNupticId(NupticId $nupticId): NupticRepresentation
    {
        $this->nupticId = $nupticId;
        return $this;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): NupticRepresentation
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
