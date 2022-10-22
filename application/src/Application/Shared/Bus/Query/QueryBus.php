<?php

namespace App\Application\Shared\Bus\Query;

interface QueryBus
{
    public function execute(Query $query): mixed;
}
