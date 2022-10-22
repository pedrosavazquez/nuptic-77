<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Nuptic;


use App\Application\Shared\Bus\Command\CommandBus;
use App\Application\Nuptic\Command\RegisterNupticCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class NupticController
{
    public function __construct(private readonly CommandBus $commandBus) {}

    public function __invoke(Request $request): JsonResponse
    {
        $contentType = $request->headers->get('Content-type');
        if ('application/json' !== $contentType) {
            throw new RequestNotValid();
        }
        $this->commandBus->execute(new RegisterNupticCommand());
        return new JsonResponse(['data' => []]);
    }
}
