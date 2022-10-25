<?php

declare(strict_types=1);

namespace App\Domain\Nuptic;

use App\Domain\Shared\Bus\Event\DomainEvent;

final class NupticWasCreated implements DomainEvent
{
    public function __construct(
        public readonly string $simulatorId,
        public readonly int $num,
        public readonly string $direction,
        public readonly int $route
    ) {
    }

    public static function fromNuptic(NupticWasCreatedRepresentation $nuptic): self
    {
        return new self($nuptic->simulatorId(), $nuptic->num(), $nuptic->direction(), $nuptic->route());
    }
}
