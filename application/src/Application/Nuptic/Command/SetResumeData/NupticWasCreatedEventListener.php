<?php

declare(strict_types=1);

namespace App\Application\Nuptic\Command\SetResumeData;

use App\Domain\Nuptic\NupticWasCreated;
use DateTimeImmutable;
use Redis;

final class NupticWasCreatedEventListener
{
    private const RESUME_DATA = 'resumeData';
    private const SOUTH = 'South';
    private const NORTH = 'North';
    private const EAST = 'East';
    private const WEST = 'West';

    public function __construct(private readonly Redis $redis)
    {
    }

    public function __invoke(NupticWasCreated $event)
    {
        $todayKey = self::RESUME_DATA . (new DateTimeImmutable())->format('Ymd');
        if ($event->num === 1) {
            $this->resetData($todayKey);
        }

        $storedData = json_decode($this->redis->get($todayKey), true, 512, JSON_THROW_ON_ERROR);
        $storedData[$event->direction] += 1;
        $storedData["Route"] += $event->route;
        $this->redis->set($todayKey, json_encode($storedData, JSON_THROW_ON_ERROR, 512));
    }

    private function resetData(string $todayKey): void
    {
        $resetData = [
            self::SOUTH => 0,
            self::NORTH => 0,
            self::WEST => 0,
            self::EAST => 0,
            "Route" => 0
        ];
        $this->redis->set(
            $todayKey,
            json_encode($resetData, JSON_THROW_ON_ERROR, 512)
        );
    }
}
