<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\CustomType;

use App\Domain\Nuptic\Num;

final class NumType extends AbstractIntType
{
    public const NAME = 'num';
    protected function getNameType(): string
    {
        return self::NAME;
    }

    protected function getEntityClass(): string
    {
        return Num::class;
    }
}
