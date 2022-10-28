<?php

declare(strict_types=1);

namespace App\Application\Shared\Bus;

use ReflectionClass;
use Stringable;

abstract class BusMessage implements Stringable
{
    public function __toString(): string
    {
        $className = (new ReflectionClass($this))->getShortName();

        return sprintf(
            'Executing "%s" with values (%s)',
            $className,
            json_encode($this->getMessageAttributes())
        );
    }

    protected function getMessageAttributes(): array
    {
        return get_object_vars($this);
    }
}
