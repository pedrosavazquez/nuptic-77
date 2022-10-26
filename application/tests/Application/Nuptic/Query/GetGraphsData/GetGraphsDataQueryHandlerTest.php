<?php

declare(strict_types=1);

namespace App\Tests\Application\Nuptic\Query\GetGraphsData;

use App\Application\Nuptic\Query\GetGraphsData\GetGraphsDataQuery;
use App\Application\Nuptic\Query\GetGraphsData\GetGraphsDataQueryHandler;
use App\Domain\Shared\Cache\CacheRepository;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class GetGraphsDataQueryHandlerTest extends TestCase
{
    private const SOUTH = 'South';
    private const NORTH = 'North';
    private const EAST = 'East';
    private const WEST = 'West';

    private GetGraphsDataQueryHandler $handler;
    private CacheRepository|MockObject $cacheRepository;

    protected function setUp(): void
    {
        $this->cacheRepository = $this->createMock(CacheRepository::class);
        $this->handler = new GetGraphsDataQueryHandler($this->cacheRepository);
    }

    public function testMustFailIfRepositoryFails(): void
    {
        $this->expectException(Exception::class);
        $this->cacheRepository->method('get')->willThrowException(new Exception());

        $this->handler->__invoke(new GetGraphsDataQuery());
    }

    public function testMustPassIfReturnsRequiredData(): void
    {
        $returnedData = [
            self::SOUTH => 1,
            self::NORTH => 3,
            self::WEST => 0,
            self::EAST => 1,
            "Route" => ['1' => 10, '2' => 10, '3' => 15, '4' => 11, '5' => 12]
        ];
        $this->cacheRepository->method('get')->willReturn(json_encode($returnedData, JSON_THROW_ON_ERROR, 512));
        $response = $this->handler->__invoke(new GetGraphsDataQuery());
        self::assertEqualsCanonicalizing($returnedData, $response);
    }

}
