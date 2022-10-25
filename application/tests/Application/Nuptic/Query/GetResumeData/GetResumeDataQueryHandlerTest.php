<?php

declare(strict_types=1);

namespace App\Tests\Application\Nuptic\Query\GetResumeData;

use App\Application\Nuptic\Query\GetResumeData\GetResumeDataQuery;
use App\Application\Nuptic\Query\GetResumeData\GetResumeDataQueryHandler;
use App\Domain\Shared\Cache\CacheRepository;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class GetResumeDataQueryHandlerTest extends TestCase
{
    private const SOUTH = 'South';
    private const NORTH = 'North';
    private const EAST = 'East';
    private const WEST = 'West';

    private GetResumeDataQueryHandler $handler;
    private MockObject|CacheRepository $cacheRepository;

    protected function setUp(): void
    {
        $this->cacheRepository = $this->createMock(CacheRepository::class);
        $this->handler = new GetResumeDataQueryHandler($this->cacheRepository);
    }

    public function testMustFailIfCacheRepositoryFails(): void
    {
        $this->expectException(Exception::class);
        $this->cacheRepository->method('get')->willThrowException(new Exception());

        $this->runHandler();
    }

    public function testMustPassWhenReturnDirectionWithHighestRepetition(): void
    {
        $this->cacheRepository->method('get')->willReturn([
            self::SOUTH => 10,
            self::NORTH => 5,
            self::WEST => 40,
            self::EAST => 5,
            "Route" => 115
        ]);

        $response = $this->runHandler();
        $expectedResponse = ['Direction' => self::WEST, 'Route' => 115];
        self::assertEqualsCanonicalizing($expectedResponse, $response);
    }

    private function runHandler(): array
    {
        return $this->handler->__invoke(new GetResumeDataQuery());
    }
}
