<?php

declare(strict_types=1);

namespace App\Domain\Nuptic;

use App\Domain\Shared\Entity\IntVO;

final class Num implements IntVO
{
    private const MIN_VALUE = 1;
    private const MAX_VALUE = 60;

    public readonly int $value;

    private function __construct(int $num)
    {
        $this->isValidNumOrFail($num);
        $this->value = $num;
    }

    public function toInt(): int
    {
        return $this->value;
    }

    private function isValidNumOrFail(int $num): void
    {
        if ($num < self::MIN_VALUE || $num > self::MAX_VALUE) {
            throw NumNotValid::fromNum($num);
        }
    }

    public static function fromInt(int $num): static
    {
        return new self($num);
    }
}
