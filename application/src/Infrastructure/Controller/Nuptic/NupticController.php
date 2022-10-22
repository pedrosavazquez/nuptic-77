<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Nuptic;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class NupticController
{
    public function __invoke(Request $request): JsonResponse
    {
        $contentType = $request->headers->get('Content-type');
        if ('application/json' !== $contentType) {
            throw new RequestNotValid();
        }
        return new JsonResponse();
    }
}
