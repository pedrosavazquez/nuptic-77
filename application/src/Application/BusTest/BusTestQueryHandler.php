<?php

namespace App\Application\BusTest;

use App\Application\Shared\Bus\Query\QueryHandler;

class BusTestQueryHandler implements QueryHandler
{
    public function __invoke(BusTestQuery $query)
    {
        throw new \Exception();
    }
}