<?php

namespace App\Domain\Shared\Entity;

interface IntVO
{
    public static function fromInt(int $value): static;
}
