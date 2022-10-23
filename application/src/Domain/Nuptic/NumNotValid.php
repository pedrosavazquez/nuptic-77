<?php

declare(strict_types=1);

namespace App\Domain\Nuptic;

use DomainException;

final class NumNotValid extends DomainException
{
    private const MIN_VALUE = 1;
    private const MAX_VALUE = 60;

    public static function fromNum(int $num) : self
    {
        return new self(
            sprintf(
                'Num must be between %d and %d, %d is not a valid num',
                self::MIN_VALUE,
                self::MAX_VALUE,
                $num
                ));
    }
}
