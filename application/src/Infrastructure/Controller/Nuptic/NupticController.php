<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Nuptic;


use Symfony\Component\HttpFoundation\JsonResponse;

final class NupticController {

    public function __invoke(): JsonResponse
    {
        return new JsonResponse();
    }
}
