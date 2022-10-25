<?php

declare(strict_types=1);

namespace App\Domain\Nuptic;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class NupticId
{
    private function __construct(private readonly UuidInterface $id)
    {}

    public function __toString(): string
    {
        return (string)$this->id;
    }

    public static function fromString(string $uuid) : self
    {
        return new self(Uuid::fromString($uuid));
    }
}
