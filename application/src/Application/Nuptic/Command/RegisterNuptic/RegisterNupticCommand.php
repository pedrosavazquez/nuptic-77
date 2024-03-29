<?php

declare(strict_types=1);

namespace App\Application\Nuptic\Command\RegisterNuptic;

use App\Application\Shared\Bus\Command\Command;

final class RegisterNupticCommand extends Command
{
    public function __construct(
        public readonly string $id,
        public readonly string $simulatorId,
        public readonly int $num,
        public readonly string $direction,
        public readonly int $route,
    ) {}
}
