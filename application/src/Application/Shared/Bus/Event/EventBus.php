<?php

declare(strict_types=1);

namespace App\Application\Shared\Bus\Event;

use App\Domain\Shared\Bus\Event\DomainEvent;

interface EventBus
{
    public function handle(DomainEvent $event): void;

    /** @param DomainEvent[] $events */
    public function handleBatch(array $events): void;
}
