<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Controller\Nuptic;

use App\Application\Shared\Bus\Command\CommandBus;
use App\Infrastructure\Controller\Nuptic\NupticController;
use App\Infrastructure\Controller\Nuptic\RequestNotValid;
use Exception;use PHPUnit\Framework\MockObject\MockObject;use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class NupticControllerTest extends TestCase
{
    private  NupticController $controller;
    private  MockObject|CommandBus $commandBus;

    protected function setUp(): void
    {
        $this->commandBus = $this->createMock(CommandBus::class);
        $this->controller = new NupticController($this->commandBus);
    }


    public function testMustFailIfRequestIsNotJsonContent(): void
    {
        $this->expectException(RequestNotValid::class);
        $request = new Request();
        $request->headers->add(['Content-type' => 'text/csv']);
        $this->controller->__invoke($request);
    }

    public function testGivenAValidRequestTestMustReturnAJsonResponseWithADataKey(): void
    {
        $request = new Request();
        $request->headers->add(['Content-type' => 'application/json']);
        $response = $this->controller->__invoke($request);
        $content = json_decode($response->getContent(), associative: true);
        self::assertArrayHasKey('data', $content);
    }

    public function testMustFailIfCommandBusFails(): void
    {
        $this->expectException(Exception::class);
        $this->commandBus->expects(self::once())->method('execute')->willThrowException(new Exception());

        $request = new Request();
        $request->headers->add(['Content-type' => 'application/json']);
        $this->controller->__invoke($request);
    }
}
