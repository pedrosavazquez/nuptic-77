<?php

namespace App\Application\Shared\Bus\Command;

interface CommandBus
{
    public function execute(Command $command): mixed;
}
