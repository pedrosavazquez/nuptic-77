<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Nuptic;

use App\Application\Nuptic\Query\GetGraphsData\GetGraphsDataQuery;
use App\Application\Shared\Bus\Query\QueryBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class GetGraphsDataController
{

    public function __construct(public readonly QueryBus $queryBus)
    {
    }

    #[Route('/nuptic-77/graphsData', 'graphsData', methods: [Request::METHOD_GET])]
    public function __invoke(): JsonResponse
    {
        $graphicsData = $this->queryBus->execute(new GetGraphsDataQuery());
        return new JsonResponse(['data' => $graphicsData]);
    }
}
