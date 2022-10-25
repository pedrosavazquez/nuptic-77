<?php

declare(strict_types=1);

namespace App\Domain\Nuptic;

final class NupticWasCreatedRepresentation extends NupticRepresentation
{
    public function simulatorId(): string
    {
        return (string)$this->simulatorId;
    }

    public function num(): int
    {
        return $this->num->toInt();
    }

    public function direction(): string
    {
        return (string)$this->direction;
    }

    public function route(): int
    {
        return $this->route->toInt();
    }
}
