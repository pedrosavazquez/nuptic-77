<?php

declare(strict_types=1);

namespace App\Domain\Nuptic;

use DomainException;

final class DirectionNotValid extends DomainException
{
    public static function fromDirection(string $direction): self
    {
        return new self(sprintf('"%s" is not a valid direction', $direction));
    }
}
