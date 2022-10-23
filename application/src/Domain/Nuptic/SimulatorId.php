<?php

declare(strict_types=1);

namespace App\Domain\Nuptic;

final class SimulatorId
{

    private function __construct(public readonly string $id)
    {}

    public static function  fromString(string $simulatorId): self
    {
        return new self($simulatorId);
    }
}
