<?php

declare(strict_types=1);

namespace App\Tests\Application\Nuptic;

use App\Domain\Nuptic\Direction;
use App\Domain\Nuptic\Num;
use App\Domain\Nuptic\Nuptic;
use App\Domain\Nuptic\NupticId;
use App\Domain\Nuptic\Route;
use App\Domain\Nuptic\SimulatorId;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use ReflectionClass;

final class NupticMother
{
    private const DIRECTIONS = [
        'North', 'South', 'East', 'West',
    ];

    public static function create(
        ?NupticId $nupticId = null,
        ?SimulatorId $simulatorId = null,
        ?Num $num = null,
        ?Direction $direction = null,
        ?Route $route = null,
    ): Nuptic
    {
        $nupticId = $nupticId ?? NupticId::fromString((string)Uuid::uuid4());
        $simulatorId = $simulatorId ?? SimulatorId::fromString((string)Uuid::uuid4());
        $num = $num ?? Num::fromInt(random_int(1, 60));
        $direction = $direction ?? Direction::fromString(self::DIRECTIONS[random_int(0, 3)]);
        $route = $route ?? Route::fromInt(random_int(10, 20));

        $reflectedNuptic = new ReflectionClass(Nuptic::class);
        $nuptic = $reflectedNuptic->newInstanceWithoutConstructor();

        $reflectedNupticId = $reflectedNuptic->getProperty('nupticId');
        $reflectedNupticId->setAccessible(true);
        $reflectedNupticId->setValue($nuptic, $nupticId);

        $reflectedSimulatorId = $reflectedNuptic->getProperty('simulatorId');
        $reflectedSimulatorId->setAccessible(true);
        $reflectedSimulatorId->setValue($nuptic, $simulatorId);

        $reflectedNum = $reflectedNuptic->getProperty('num');
        $reflectedNum->setAccessible(true);
        $reflectedNum->setValue($nuptic, $num);

        $reflectedDirection = $reflectedNuptic->getProperty('direction');
        $reflectedDirection->setAccessible(true);
        $reflectedDirection->setValue($nuptic, $direction);

        $reflectedRoute = $reflectedNuptic->getProperty('route');
        $reflectedRoute->setAccessible(true);
        $reflectedRoute->setValue($nuptic, $route);

        $reflectedRoute = $reflectedNuptic->getProperty('createdAt');
        $reflectedRoute->setAccessible(true);
        $reflectedRoute->setValue($nuptic, new DateTimeImmutable());

        return $nuptic;
    }
}