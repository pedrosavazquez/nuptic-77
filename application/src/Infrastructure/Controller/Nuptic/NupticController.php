<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Nuptic;


use App\Application\Nuptic\Command\RegisterNuptic\RegisterNupticCommand;
use App\Application\Nuptic\Query\GetResumeData\GetResumeDataQuery;
use App\Application\Shared\Bus\Command\CommandBus;
use App\Application\Shared\Bus\Query\QueryBus;
use Exception;
use JsonException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class NupticController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly QueryBus $queryBus,
        private readonly FailureProvoker $failureProvoker,
    ) {
    }

    /**
     * @throws JsonException
     */
    #[Route('/nuptic-77', 'nuptic77', methods: Request::METHOD_POST)]
    public function __invoke(Request $request): JsonResponse
    {
        ($this->failureProvoker)();
        $contentType = $request->headers->get('Content-type');
        $this->isValidContentTypeOrFail($contentType);
        $contentBody = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        return $this->runCommand($contentBody);
    }

    private function isValidContentTypeOrFail(?string $contentType): void
    {
        if ('application/json' !== $contentType) {
            throw RequestNotValid::fromContentType($contentType);
        }
    }

    private function runCommand(array $contentBody): JsonResponse
    {
        try {
            $id = (string)Uuid::uuid4();
            $command = new RegisterNupticCommand(
                $id,
                $contentBody['simulator_id'],
                $contentBody['num'],
                $contentBody['direction'],
                $contentBody['route'],
            );
        } catch (Exception) {
            throw RequestNotValid::forBodyContent();
        }
        $this->commandBus->execute($command);
        $response = ['data' => ['id' => $id]];
        $response = $this->getResumeDataIfNeeded($contentBody['num'], $response);

        return new JsonResponse($response);
    }

    public function getResumeDataIfNeeded($num, array $response): array
    {
        if ($num === 60) {
            $resumeData = $this->queryBus->execute(new GetResumeDataQuery());
            $response['data']['resume_data'] = $resumeData;
        }
        return $response;
    }
}
