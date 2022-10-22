<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Nuptic;


use App\Application\Shared\Bus\Command\CommandBus;
use App\Application\Nuptic\Command\RegisterNupticCommand;
use Exception;use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class NupticController
{
    public function __construct(private readonly CommandBus $commandBus) {}

    public function __invoke(Request $request): JsonResponse
    {
        $contentType = $request->headers->get('Content-type');
        if ('application/json' !== $contentType) {
            throw RequestNotValid::fromContentType($contentType);
        }
        $contentBody = json_decode($request->getContent(), true, JSON_THROW_ON_ERROR, 512);

        try{
            $command=new RegisterNupticCommand(
                $contentBody['simulator_id'],
                $contentBody['num'],
                $contentBody['direction'],
                $contentBody['route'],
            );
        }catch(Exception){
            throw RequestNotValid::forBodyContent();
        }


        $this->commandBus->execute($command);
        return new JsonResponse(['data' => []]);
    }
}
