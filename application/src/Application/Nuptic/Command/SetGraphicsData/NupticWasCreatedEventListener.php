<?php

declare(strict_types=1);

namespace App\Application\Nuptic\Command\SetGraphicsData;

use App\Domain\Nuptic\NupticWasCreated;
use DateTimeImmutable;
use Redis;

final class NupticWasCreatedEventListener
{
    private const GRAPHICS_DATA = 'graphicsData_';
    private const SOUTH = 'South';
    private const NORTH = 'North';
    private const EAST = 'East';
    private const WEST = 'West';
    private const DIRECTION = 'Direction';
    private const ROUTE = 'Route';

    public function __construct(private readonly Redis $redis)
    {
    }

    public function __invoke(NupticWasCreated $event)
    {
        $todayKey = self::GRAPHICS_DATA . (new DateTimeImmutable())->format('Ymd');
        if ($event->num === 1) {
            $this->resetData($todayKey);
        }

        $json = $this->redis->get($todayKey);
        $storedData = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        $storedData[self::DIRECTION][$event->direction] += 1;
        $storedData[self::ROUTE][$event->num] = $event->route;

        $valueToStore = json_encode($storedData, JSON_THROW_ON_ERROR, 512);

        $this->redis->set($todayKey, $valueToStore);
    }

    private function resetData(string $todayKey): void
    {
        $resetData = [
            self::DIRECTION => [
                self::SOUTH => 0,
                self::NORTH => 0,
                self::WEST => 0,
                self::EAST => 0,
            ],
            self::ROUTE => []
        ];
        $this->redis->set(
            $todayKey,
            json_encode($resetData, JSON_THROW_ON_ERROR, 512)
        );
    }
}
