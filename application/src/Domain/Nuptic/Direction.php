<?php

declare(strict_types=1);

namespace App\Domain\Nuptic;

final class Direction
{
    private const EAST = 'East';
    private const WEST = 'West';
    private const NORTH = 'North';
    private const SOUTH = 'South';

    private const VALID_DIRECTIONS = [
        self::EAST,
        self::WEST,
        self::NORTH,
        self::SOUTH,
    ];

    public readonly string $direction;

    private function __construct(string $direction)
    {
        $this->isValidDirectionOrFail($direction);
        $this->direction = $direction;
    }

    public function __toString(): string
    {
        return $this->direction;
    }

    private function isValidDirectionOrFail(string $direction): void
    {
        if (!in_array($direction, self::VALID_DIRECTIONS)) {
            throw DirectionNotValid::fromDirection($direction);
        }
    }

    public static function fromString(string $direction): self
    {
        return new self($direction);
    }
}
