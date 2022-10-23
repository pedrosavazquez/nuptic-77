<?php

declare(strict_types=1);

namespace App\Domain\Nuptic;

use DomainException;

final class DirectionNotValid extends DomainException
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

    public static function fromDirection(string $direction): self
    {
        return new self(
            sprintf(
                '%s is not a valid direction, valid ones are "%s"',
                $direction,
                implode(',', self::VALID_DIRECTIONS)
        ));
    }
}
