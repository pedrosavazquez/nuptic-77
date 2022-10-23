<?php

declare(strict_types=1);

namespace App\Infrastructure\Middleware;

use App\Application\Nuptic\Command\RegisterNupticCommand;

final class DirectionRegister
{
    private const DIRECTION_REQUEST_LOG_PATH='directionRequestLogPath';
    private const EAST = 'East';

    public function __invoke(RegisterNupticCommand $command): void
    {
        if (self::EAST !== $command->direction) {
            return;
        }
        $path = getenv(self::DIRECTION_REQUEST_LOG_PATH);
        file_put_contents($path, json_encode($command, JSON_THROW_ON_ERROR, 512));
    }
}
