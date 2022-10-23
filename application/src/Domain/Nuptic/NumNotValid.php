<?php

declare(strict_types=1);

namespace App\Domain\Nuptic;

use DomainException;

final class NumNotValid extends DomainException
{
    public static function fromNum(int $num) : self
    {
        return new self(sprintf('"%d" is not a valid num', $num));
    }
}
