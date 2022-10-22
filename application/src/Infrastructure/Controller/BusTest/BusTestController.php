<?php

namespace App\Infrastructure\Controller\BusTest;

use App\Application\BusTest\BusTestQuery;
use App\Application\Shared\Bus\Query\QueryBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class BusTestController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    #[Route('/busTest', name: 'bus_test', methods: [Request::METHOD_GET])]
    public function __invoke(): JsonResponse
    {
        $response = $this->queryBus->execute(new BusTestQuery());
        return new JsonResponse(['data' => $response]);
    }
}
