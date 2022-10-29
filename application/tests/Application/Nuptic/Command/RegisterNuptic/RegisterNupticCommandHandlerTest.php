<?php

declare(strict_types=1);

namespace App\Tests\Application\Nuptic\Command\RegisterNuptic;

use App\Application\Nuptic\Command\RegisterNuptic\RegisterNupticCommand;
use App\Application\Nuptic\Command\RegisterNuptic\RegisterNupticCommandHandler;
use App\Application\Shared\Bus\Event\EventBus;
use App\Domain\Nuptic\NupticWasCreated;
use App\Domain\Nuptic\NupticWriteRepository;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Redis;


final class RegisterNupticCommandHandlerTest extends TestCase
{
    private RegisterNupticCommandHandler $handler;
    private MockObject|NupticWriteRepository $nupticRepository;
    private EventBus|MockObject $eventBus;
    private MockObject|Redis $redis;

    protected function setUp(): void
    {
        $this->nupticRepository = $this->createMock(NupticWriteRepository::class);
        $this->eventBus = $this->createMock(EventBus::class);
        $this->redis = $this->createMock(Redis::class);
        $this->handler = new RegisterNupticCommandHandler($this->nupticRepository, $this->eventBus, $this->redis);
    }

    public function testMustFailIfRepositoryFails(): void
    {
        $this->expectException(Exception::class);
        $this->nupticRepository->expects(self::once())->method('save')->willThrowException(new Exception());
        $this->runHandler();
    }

    public function testMustFailIfEventBusFails(): void
    {
        $this->expectException(Exception::class);
        $this->nupticRepository->expects(self::once())->method('save');
        $this->eventBus->expects(self::once())->method('handleBatch')->willThrowException(new Exception());
        $this->runHandler();
    }

    public function testMustPassWhenHandlerEmitsTheEvents(): void
    {
        $this->nupticRepository->expects(self::once())->method('save');
        $this->eventBus->expects(self::once())->method('handleBatch')->with(self::callback(
            static function (array $events) {
                if (0 === count($events)) {
                    return false;
                }
                foreach ($events as $event) {
                    if (!$event instanceof NupticWasCreated) {
                        return false;
                    }
                }
                return true;
            }
        ));
        $this->runHandler();
    }

    public function testMustFailIfRedisConnectionFails(): void
    {
        $this->expectException(Exception::class);
        $this->nupticRepository->expects(self::once())->method('save');
        $this->redis->expects(self::once())->method('incr')->willThrowException(new Exception());

        $this->runHandler();
    }

    private function runHandler(): void
    {
        $command = new RegisterNupticCommand((string)Uuid::uuid4(), (string)Uuid::uuid4(), 1, 'North', 20);
        $this->handler->__invoke($command);
    }
}
