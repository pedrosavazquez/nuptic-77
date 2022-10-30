<?php

declare(strict_types=1);

namespace App\Tests\Application\Nuptic\Query\GetGraphsData;

use App\Application\Nuptic\Query\GetGraphsData\GetGraphsDataQuery;
use App\Application\Nuptic\Query\GetGraphsData\GetGraphsDataQueryHandler;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Redis;

final class GetGraphsDataQueryHandlerTest extends TestCase
{
    private const SOUTH = 'South';
    private const NORTH = 'North';
    private const EAST = 'East';
    private const WEST = 'West';

    private GetGraphsDataQueryHandler $handler;
    private Redis|MockObject $redis;

    protected function setUp(): void
    {
        $this->redis = $this->createMock(Redis::class);
        $this->handler = new GetGraphsDataQueryHandler($this->redis);
    }

    public function testMustFailIfRepositoryFails(): void
    {
        $this->expectException(Exception::class);
        $this->redis->method('get')->willThrowException(new Exception());

        $this->handler->__invoke(new GetGraphsDataQuery());
    }

    public function testMustPassIfReturnsRequiredData(): void
    {
        $returnedData = [
            "Direction" => [
                self::SOUTH => 1,
                self::NORTH => 3,
                self::WEST => 0,
                self::EAST => 1,
            ],
            "Route" => ['1' => 10, '2' => 10, '3' => 15, '4' => 11, '5' => 12]
        ];
        $this->redis->method('get')->willReturn(json_encode($returnedData, JSON_THROW_ON_ERROR, 512));
        $response = $this->handler->__invoke(new GetGraphsDataQuery());
        self::assertEqualsCanonicalizing($returnedData, $response);
    }

}
