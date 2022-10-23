<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Nuptic;

use RuntimeException;

final class ProgrammedError extends RuntimeException {
    public function __construct()
    {
        parent::__construct('This is a provoked error for the 10% of the requests');
    }
}
