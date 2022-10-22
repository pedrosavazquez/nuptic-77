<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Controller\Nuptic;

use App\Infrastructure\Controller\Nuptic\NupticController;
use App\Infrastructure\Controller\Nuptic\RequestNotValid;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class NupticControllerTest extends TestCase
{

    public function testMustFailIfRequestIsNotJsonContent(): void
    {
        $this->expectException(RequestNotValid::class);
        $controller = new NupticController();
        $request = new Request();
        $request->headers->add(['Content-type' => 'text/csv']);
        $controller->__invoke($request);
    }

    public function testGivenAValidRequestTestMustReturnAJsonResponseWithADataKey(): void
    {
        $controller = new NupticController();
        $request = new Request();
        $request->headers->add(['Content-type' => 'application/json']);
        $response = $controller->__invoke($request);
        $content = json_decode($response->getContent(), associative: true);
        self::assertArrayHasKey('data', $content);
    }
}
