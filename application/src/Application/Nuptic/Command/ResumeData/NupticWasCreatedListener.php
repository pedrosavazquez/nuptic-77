<?php

declare(strict_types=1);

namespace App\Application\Nuptic\Command\ResumeData;

use App\Domain\Nuptic\NupticWasCreated;
use App\Domain\Shared\Cache\CacheRepository;
use DateTimeImmutable;

final class NupticWasCreatedListener
{
    private const RESUME_DATA = 'resumeData';
    private const SOUTH = 'South';
    private const NORTH = 'North';
    private const EAST = 'East';
    private const WEST = 'West';

    public function __construct(private readonly CacheRepository $cacheRepository)
    {
    }

    public function __invoke(NupticWasCreated $event)
    {
        $todayKey = self::RESUME_DATA . (new DateTimeImmutable())->format('Ymd');
        if ($event->num === 1) {
            $resetData = [
                self::SOUTH => 0,
                self::NORTH => 0,
                self::WEST => 0,
                self::EAST => 0,
                "Route" => 0
            ];
            $this->cacheRepository->set(
                $todayKey,
                json_encode($resetData, JSON_THROW_ON_ERROR, 512)
            );
        }

        $storedData = json_decode($this->cacheRepository->get($todayKey), true, 512, JSON_THROW_ON_ERROR);
        $storedData[$event->direction] += 1;
        $storedData["Route"] += $event->route;
        $this->cacheRepository->set($todayKey, json_encode($storedData, JSON_THROW_ON_ERROR, 512));
    }
}
