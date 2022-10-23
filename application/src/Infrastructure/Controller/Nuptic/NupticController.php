<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Nuptic;


use App\Application\Shared\Bus\Command\CommandBus;
use App\Application\Nuptic\Command\RegisterNupticCommand;
use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class NupticController
{
    public function __construct(private readonly CommandBus $commandBus)
    {}

    #[Route('/nuptic77', 'nuptic77', methods: [Request::METHOD_POST])]
    public function __invoke(Request $request): JsonResponse
    {
        $contentType = $request->headers->get('Content-type');
        $this->isValidContentTypeOrFail($contentType);
        $contentBody = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        return $this->runCommand($contentBody);
    }

    private function isValidContentTypeOrFail(?string $contentType):void
    {
        if ('application/json' !== $contentType) {
            throw RequestNotValid::fromContentType($contentType);
        }
    }

    private function runCommand(array $contentBody):JsonResponse
    {
        try {
            $id = (string)Uuid::uuid4();
            $command=new RegisterNupticCommand(
                $id,
                $contentBody['simulator_id'],
                $contentBody['num'],
                $contentBody['direction'],
                $contentBody['route'],
            );

            $this->commandBus->execute($command);
            return new JsonResponse(['data' => ['id' => $id]]);
        } catch (Exception) {
            throw RequestNotValid::forBodyContent();
        }
    }
}
