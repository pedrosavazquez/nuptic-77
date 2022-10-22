<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Nuptic;

use RuntimeException;

final class RequestNotValid extends RuntimeException
{
    public static function fromContentType(string $contentType): self
    {
        return new self(sprintf('Only Content-type: application/json is allowed, not "%s"', $contentType));
    }

    public static function forBodyContent(): self
    {
        return new self('Some missing params, obligatory fields are simulator_id, num, direction and route');
    }
}
