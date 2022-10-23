<?php

declare(strict_types=1);

namespace App\Domain\Nuptic;

final class Num
{
    private const MIN_VALUE = 1;
    private const MAX_VALUE = 60;

    public readonly int $num;

    private function __construct(int $num)
    {
        $this->isValidNumOrFail($num);
        $this->num = $num;
    }

    private function isValidNumOrFail(int $num): void
    {
        if ($num < self::MIN_VALUE || $num > self::MAX_VALUE) {
            throw NumNotValid::fromNum($num);
        }
    }

    public static function fromInt(int $num): self
    {
        return new self($num);
    }
}
