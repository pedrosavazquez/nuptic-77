<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Controller\Nuptic;


use PHPUnit\Framework\TestCase;
use App\Infrastructure\Controller\Nuptic\NupticController;
use Symfony\Component\HttpFoundation\JsonResponse;

class NupticControllerTest extends TestCase
{
    public function testMustInvokeNupticController(): void
    {
        $controller = new NupticController();
        $response = $controller->__invoke();
        self::assertInstanceOf(JsonResponse::class, $response);
    }
}
