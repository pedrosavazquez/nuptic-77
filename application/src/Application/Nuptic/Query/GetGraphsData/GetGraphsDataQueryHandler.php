<?php

declare(strict_types=1);

namespace App\Application\Nuptic\Query\GetGraphsData;

use Redis;

final class GetGraphsDataQueryHandler
{
    private const GRAPHICS_DATA = 'graphicsData_';

    public function __construct(private readonly Redis $redis)
    {
    }

    public function __invoke(GetGraphsDataQuery $query): array
    {
        $todayDate = (new \DateTimeImmutable())->format('Ymd');
        $graphsData = $this->redis->get(self::GRAPHICS_DATA.$todayDate);

        return json_decode($graphsData, true, 512, JSON_THROW_ON_ERROR);
    }
}
