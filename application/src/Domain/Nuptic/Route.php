<?php

declare(strict_types=1);

namespace App\Domain\Nuptic;

final class Route
{
    private const MIN_VALUE = 1;
    private const MAX_VALUE = 60;

    public readonly int $route;

    private function __construct(int $route)
    {
        $this->isValidNumOrFail($route);
        $this->route = $route;
    }

    private function isValidNumOrFail(int $route): void
    {
        if ($route < self::MIN_VALUE || $route > self::MAX_VALUE) {
            throw RouteNotValid::fromRoute($route);
        }
    }

    public static function fromInt(int $route): self
    {
        return new self($route);
    }
}
