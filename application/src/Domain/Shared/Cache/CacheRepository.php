<?php

namespace App\Domain\Shared\Cache;

interface CacheRepository {
    public function incr($key);
}
