<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Redis;

use App\Domain\Shared\Cache\CacheRepository;use Redis;

final class CacheRepositoryRedis extends Redis implements CacheRepository
{

}