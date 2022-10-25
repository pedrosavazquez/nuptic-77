<?php

namespace App\Domain\Shared\Cache;

interface CacheRepository
{
    public function incr($key);

    public function incrBy($key, $value);

    public function set($key, $value);

    public function get($key);
}
