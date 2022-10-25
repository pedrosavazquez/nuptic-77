<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\CustomType;

use App\Domain\Nuptic\Route;

final class RouteType extends AbstractIntType
{
    public const NAME = 'route';

    protected function getNameType(): string
    {
        return self::NAME;
    }

    protected function getEntityClass(): string
    {
        return Route::class;
    }
}
