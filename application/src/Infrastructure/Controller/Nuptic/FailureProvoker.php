<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Nuptic;

class FailureProvoker
{
    public function __invoke(): void
    {
        if (10 < random_int(1, 100)) {
            throw new ProgrammedError();
        }
    }
}
