<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Controller\Nuptic;

use App\Application\Shared\Bus\Command\CommandBus;
use App\Application\Shared\Bus\Query\QueryBus;
use App\Infrastructure\Controller\Nuptic\FailureProvoker;
use App\Infrastructure\Controller\Nuptic\NupticController;
use App\Infrastructure\Controller\Nuptic\RequestNotValid;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class NupticControllerTest extends TestCase
{
    private NupticController $controller;
    private MockObject|CommandBus $commandBus;
    private MockObject|QueryBus $queryBus;

    protected function setUp(): void
    {
        $this->commandBus = $this->createMock(CommandBus::class);
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->failureProvoker = $this->createMock(FailureProvoker::class);
        $this->controller = new NupticController($this->commandBus, $this->queryBus, $this->failureProvoker);
    }


    public function testMustFailIfRequestIsNotJsonContent(): void
    {
        $this->expectException(RequestNotValid::class);
        $request = new Request();
        $request->headers->add(['Content-type' => 'text/csv']);
        $this->controller->__invoke($request);
    }

    /** @dataProvider requestProvider */
    public function testGivenAValidRequestTestMustReturnAJsonResponseWithADataKey(array $requestData): void
    {
        $response = $this->runValidRequest($requestData);
        $content = json_decode($response->getContent(), associative: true);
        self::assertArrayHasKey('data', $content);
        self::assertArrayHasKey('id', $content['data']);
        self::assertTrue(Uuid::isValid($content['data']['id']));
    }

    public function testMustFailIfCommandBusFails(): void
    {
        $this->expectException(Exception::class);
        $this->commandBus->expects(self::once())->method('execute')->willThrowException(new Exception());

        $request = new Request();
        $request->headers->add(['Content-type' => 'application/json']);
        $data = [
            'simulator_id' => Uuid::uuid4(),
            'num' => 1,
            'direction' => 'East',
            'route' => 10
        ];
        $this->runValidRequest($data);
    }

    public function testMustFailIfRequestBodyIsIncomplete(): void
    {
        $this->expectException(RequestNotValid::class);
        $this->commandBus->expects(self::never())->method('execute');

        $request = new Request(content: '[]');
        $request->headers->add(['Content-type' => 'application/json']);
        $this->controller->__invoke($request);
    }

    public function testMustFailIfQueryBusFails(): void
    {
        $this->expectException(Exception::class);
        $requestData = [
            'simulator_id' => Uuid::uuid4(),
            'num' => 60,
            'direction' => 'East',
            'route' => 10
        ];
        $this->queryBus->method('execute')->willThrowException(new Exception());
        $response = $this->runValidRequest($requestData);
        $content = json_decode($response->getContent(), associative: true);
    }

    public function testMustPassIfResponseIncludesResumeDataOnNumSixty(): void
    {
        $requestData = [
            'simulator_id' => Uuid::uuid4(),
            'num' => 60,
            'direction' => 'East',
            'route' => 10
        ];
        $this->queryBus->method('execute')->willReturn(['Direction' => 'East', 'Route' => 115]);
        $response = $this->runValidRequest($requestData);
        $content = json_decode($response->getContent(), associative: true);
        self::assertArrayHasKey('data', $content);
        self::assertArrayHasKey('id', $content['data']);
        self::assertArrayHasKey('resume_data', $content['data']);
        self::assertTrue(Uuid::isValid($content['data']['id']));
    }

    private function runValidRequest(array $requestContent): JsonResponse
    {
        $request = new Request(
            content: json_encode($requestContent, JSON_THROW_ON_ERROR)
        );
        $request->headers->add(['Content-type' => 'application/json']);
        return $this->controller->__invoke($request);
    }

    public function requestProvider(): array
    {
        return [
            [
                [
                    'simulator_id' => Uuid::uuid4(),
                    'num' => 1,
                    'direction' => 'East',
                    'route' => 10
                ]
            ],
            [
                [
                    'simulator_id' => Uuid::uuid4(),
                    'num' => 2,
                    'direction' => 'North',
                    'route' => 20
                ]
            ]
        ];
    }
}
