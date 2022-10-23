<?php

declare(strict_types=1);

namespace App\Domain\Nuptic;

use App\Domain\Shared\Bus\Event\DomainEvent;

final class NupticWasCreated implements DomainEvent
{
    public static function fromNuptic(Nuptic $nuptic): self
    {
        return new self();
    }
}
