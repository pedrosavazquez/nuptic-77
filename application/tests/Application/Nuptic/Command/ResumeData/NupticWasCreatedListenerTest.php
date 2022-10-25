<?php

declare(strict_types=1);

namespace App\Tests\Application\Nuptic\Command\ResumeData;

use App\Application\Nuptic\Command\ResumeData\NupticWasCreatedListener;
use App\Domain\Nuptic\Direction;
use App\Domain\Nuptic\Num;
use App\Domain\Nuptic\Nuptic;
use App\Domain\Nuptic\NupticWasCreated;
use App\Domain\Nuptic\NupticWasCreatedRepresentation;
use App\Domain\Nuptic\Route;
use App\Domain\Shared\Cache\CacheRepository;
use App\Tests\Application\Nuptic\NupticMother;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class NupticWasCreatedListenerTest extends TestCase
{

    private const SOUTH = 'South';
    private const NORTH = 'North';
    private const EAST = 'East';
    private const WEST = 'West';
    private NupticWasCreatedListener $listener;
    private CacheRepository|MockObject $cacheRepository;

    protected function setUp(): void
    {
        $this->cacheRepository = $this->createMock(CacheRepository::class);
        $this->listener = new NupticWasCreatedListener($this->cacheRepository);
    }

    public function testMustFailIfRedisFailToReset(): void
    {
        $this->expectException(Exception::class);
        $this->cacheRepository->method('set')->willThrowException(new Exception());
        $this->cacheRepository->expects(self::never())->method('get');

        $nuptic = NupticMother::create(num: Num::fromInt(1));
        $this->runListener($nuptic);
    }

    public function testMustPassIfResetIsNotDone(): void
    {
        $returnData = [
            self::SOUTH => 1,
            self::NORTH => 0,
            self::WEST => 0,
            self::EAST => 0,
            "Route" => 10,
        ];
        $this->cacheRepository->expects(self::once())->method('get')->willReturn(json_encode($returnData));
        $nuptic = NupticMother::create(
            direction: Direction::fromString(self::EAST),
            num: Num::fromInt(2),
            route: Route::fromInt(15)
        );

        $dataToStore = [
            self::SOUTH => 1,
            self::NORTH => 0,
            self::WEST => 0,
            self::EAST => 1,
            "Route" => 25,
        ];
        $jsonEncode = json_encode($dataToStore, JSON_THROW_ON_ERROR, 512);
        $todayKey = 'resumeData' . (new DateTimeImmutable())->format('Ymd');
        $this->cacheRepository->expects(self::once())->method('set')->with($todayKey, $jsonEncode);

        $this->runListener($nuptic);
    }

    private function runListener(Nuptic $nuptic): void
    {
        $event = NupticWasCreated::fromNuptic($nuptic->representedAs(new NupticWasCreatedRepresentation()));

        $this->listener->__invoke($event);
    }
}
