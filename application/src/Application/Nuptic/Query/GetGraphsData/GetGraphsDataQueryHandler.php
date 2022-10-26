<?php

declare(strict_types=1);

namespace App\Application\Nuptic\Query\GetGraphsData;

use App\Domain\Shared\Cache\CacheRepository;

final class GetGraphsDataQueryHandler
{

    public function __construct(private readonly CacheRepository $cacheRepository)
    {
    }

    public function __invoke(GetGraphsDataQuery $query): array
    {
        $todayDate = (new \DateTimeImmutable())->format('Ymd');
        $graphsData = $this->cacheRepository->get($todayDate);

        return json_decode($graphsData, true, 512, JSON_THROW_ON_ERROR);
    }
}
