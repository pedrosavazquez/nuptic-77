<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Nuptic;

use Exception;

class FailureProvoker
{
    /**
     * @throws ProgrammedError|Exception
     */
    public function __invoke(): void
    {
        if (90 < random_int(1, 100)) {
            throw new ProgrammedError();
        }
    }
}
