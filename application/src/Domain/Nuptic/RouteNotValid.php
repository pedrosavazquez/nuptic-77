<?php

declare(strict_types=1);

namespace App\Domain\Nuptic;

use DomainException;

final class RouteNotValid extends DomainException
{
    public static function fromRoute(int $route) : self
    {
        return new self(sprintf('"%d" is not a valid route', $route));
    }
}
