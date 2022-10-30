<?php

declare(strict_types=1);

namespace App\Tests\Application\Nuptic\Command\SetResumeData;

use App\Application\Nuptic\Command\SetResumeData\NupticWasCreatedEventListener;
use App\Domain\Nuptic\Direction;
use App\Domain\Nuptic\Num;
use App\Domain\Nuptic\Nuptic;
use App\Domain\Nuptic\NupticWasCreated;
use App\Domain\Nuptic\NupticWasCreatedRepresentation;
use App\Domain\Nuptic\Route;
use App\Tests\Application\Nuptic\NupticMother;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Redis;

final class NupticWasCreatedEventListenerTest extends TestCase
{
    private const SOUTH = 'South';
    private const NORTH = 'North';
    private const EAST = 'East';
    private const WEST = 'West';
    private const DIRECTION = "Direction";
    private const ROUTE = "Route";

    private NupticWasCreatedEventListener $listener;
    private Redis|MockObject $redis;

    protected function setUp(): void
    {
        $this->redis = $this->createMock(Redis::class);
        $this->listener = new NupticWasCreatedEventListener($this->redis);
    }

    public function testMustFailIfRedisFailToReset(): void
    {
        $this->expectException(Exception::class);
        $this->redis->method('set')->willThrowException(new Exception());
        $this->redis->expects(self::never())->method('get');

        $nuptic = NupticMother::create(num: Num::fromInt(1));
        $this->runListener($nuptic);
    }

    public function testMustPassIfResetIsNotDone(): void
    {
        $returnData = [
            self::DIRECTION => [
                self::SOUTH => 1,
                self::NORTH => 0,
                self::WEST => 0,
                self::EAST => 0,
            ],
            self::ROUTE => 10,
        ];
        $this->redis->expects(self::once())->method('get')->willReturn(json_encode($returnData));
        $nuptic = NupticMother::create(
            num: Num::fromInt(2),
            direction: Direction::fromString(self::EAST),
            route: Route::fromInt(15)
        );

        $dataToStore = [
            self::DIRECTION => [
                self::SOUTH => 1,
                self::NORTH => 0,
                self::WEST => 0,
                self::EAST => 1,
            ],
            self::ROUTE => 25,
        ];
        $jsonEncode = json_encode($dataToStore, JSON_THROW_ON_ERROR, 512);
        $todayKey = 'resumeData' . (new DateTimeImmutable())->format('Ymd');
        $this->redis->expects(self::once())->method('set')->with($todayKey, $jsonEncode);

        $this->runListener($nuptic);
    }

    private function runListener(Nuptic $nuptic): void
    {
        $event = NupticWasCreated::fromNuptic($nuptic->representedAs(new NupticWasCreatedRepresentation()));

        $this->listener->__invoke($event);
    }
}
