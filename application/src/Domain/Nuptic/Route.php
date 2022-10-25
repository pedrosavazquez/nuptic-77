<?php

declare(strict_types=1);

namespace App\Domain\Nuptic;

use App\Domain\Shared\Entity\IntVO;

final class Route implements IntVO
{
    private const MIN_VALUE = 1;
    private const MAX_VALUE = 60;

    public readonly int $value;

    private function __construct(int $route)
    {
        $this->isValidNumOrFail($route);
        $this->value = $route;
    }

    public function toInt(): int
    {
        return $this->value;
    }

    private function isValidNumOrFail(int $route): void
    {
        if ($route < self::MIN_VALUE || $route > self::MAX_VALUE) {
            throw RouteNotValid::fromRoute($route);
        }
    }

    public static function fromInt(int $route): static
    {
        return new self($route);
    }
}
