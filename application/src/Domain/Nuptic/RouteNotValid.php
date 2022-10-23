<?php

declare(strict_types=1);

namespace App\Domain\Nuptic;

use DomainException;

final class RouteNotValid extends DomainException
{
    private const MIN_VALUE = 10;
    private const MAX_VALUE = 20;

    public static function fromRoute(int $route) : self
    {
        return new self(
            sprintf(
                'Route must be between %d and %d, %d is not a valid route',
                self::MIN_VALUE,
                self::MAX_VALUE,
                $route
            ));
    }
}
