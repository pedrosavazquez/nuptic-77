<?php

declare(strict_types=1);

namespace App\Infrastructure\Middleware;

use App\Application\Nuptic\Command\RegisterNuptic\RegisterNupticCommand;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Messenger\Stamp\SentStamp;
use Throwable;

final class DirectionRegister implements MiddlewareInterface
{
    private const EAST = 'East';

    private LoggerInterface $logger;

    public function __construct(LoggerInterface $directionLogger)
    {
        $this->logger = $directionLogger;
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();
        dump($message);
        if (false === $message instanceof RegisterNupticCommand || self::EAST !== $message->direction) {
            return $stack->next()->handle($envelope, $stack);
        }

        $context = [
            'message' => $this->getClassName($message),
            'content' => (string)$message,
            'class' => $message::class,
        ];

        [$envelope, $exception] = $this->passMessageToNextHandler($envelope, $stack);

        $messageTemplate = $this->prepareMessageTemplate($envelope, $exception);

        $this->logMessageAndPassException($messageTemplate, $context, $exception);

        return $envelope;
    }

    private function getClassName($message): string
    {
        $fullName = $message::class;

        $parts = explode('\\', $fullName);

        return end($parts);
    }

    private function passMessageToNextHandler(Envelope $envelope, StackInterface $stack): array
    {
        $exception = null;
        try {
            $envelope = $stack->next()
                            ->handle($envelope, $stack);
        } catch (Throwable $exceptionHandlingMessage) {
            $exception = $exceptionHandlingMessage;
        }

        return [$envelope, $exception];
    }

    private function prepareMessageTemplate(Envelope $envelope, ?Throwable $exception): string
    {
        if ($envelope->last(ReceivedStamp::class)) {
            $logMessage = 'Received';
        } elseif ($envelope->last(SentStamp::class)) {
            $logMessage = 'Sent';
        } else {
            $logMessage = 'Handling';
        }

        if (null === $exception) {
            $logMessage .= ' {message} {content}';
        }

        return $logMessage;
    }

    private function logMessageAndPassException(string $logMessage, array $context, ?Throwable $exception): void
    {
        if ($exception) {
            return;
        }

        $this->logger->info($logMessage, $context);
    }
}
