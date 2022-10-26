<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Controller\Nuptic;

use App\Application\Shared\Bus\Query\QueryBus;
use App\Infrastructure\Controller\Nuptic\GetGraphsDataController;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class GetGraphsDataControllerTest extends TestCase
{
    private const SOUTH = 'South';
    private const NORTH = 'North';
    private const EAST = 'East';
    private const WEST = 'West';

    private GetGraphsDataController $controller;
    private MockObject|QueryBus $queryBus;

    protected function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->controller = new GetGraphsDataController($this->queryBus);
    }

    public function testMustFailIfQueryBusFails(): void
    {
        $this->expectException(Exception::class);
        $this->queryBus->method('execute')->willThrowException(new Exception());

        $this->controller->__invoke();
    }

    public function testMustPassGivenResponseWithRequiredInfo(): void
    {
        $returnedData = [
            self::SOUTH => 1,
            self::NORTH => 3,
            self::WEST => 0,
            self::EAST => 1,
            "Route" => ['1' => 10, '2' => 10, '3' => 15, '4' => 11, '5' => 12]
        ];
        $this->queryBus->method('execute')->willReturn($returnedData);
        $response = $this->controller->__invoke();
        $content = json_decode($response->getContent(), associative: true);

        self::assertArrayHasKey('data', $content);
        self::assertArrayHasKey('Route', $content['data']);
        self::assertArrayHasKey(self::NORTH, $content['data']);
        self::assertArrayHasKey(self::SOUTH, $content['data']);
        self::assertArrayHasKey(self::EAST, $content['data']);
        self::assertArrayHasKey(self::WEST, $content['data']);
    }
}
