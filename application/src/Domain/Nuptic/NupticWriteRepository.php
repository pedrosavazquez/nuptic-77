<?php

namespace App\Domain\Nuptic;

interface NupticWriteRepository
{

    public function save(Nuptic $nuptic): void;
}
