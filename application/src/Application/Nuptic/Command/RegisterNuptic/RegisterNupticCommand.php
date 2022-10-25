<?php

declare(strict_types=1);

namespace App\Application\Nuptic\Command\RegisterNuptic;

use App\Application\Shared\Bus\Command\Command;

final class RegisterNupticCommand implements Command
{
    public function __construct(
        public readonly string $id,
        public readonly string $simulatorId,
        public readonly int $num,
        public readonly string $direction,
        public readonly int $route,
    ) {}

    public function jsonSerialize() : array
    {
         return [
              'simulator_id' => $this->simulatorId,
              'num' => $this->num,
              'direction' => $this->direction,
              'route' => $this->route,
        ];
    }
}
