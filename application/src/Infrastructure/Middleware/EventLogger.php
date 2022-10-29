<?php

declare(strict_types=1);

namespace App\Infrastructure\Middleware;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Messenger\Stamp\SentStamp;
use Throwable;

final class EventLogger implements MiddlewareInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $eventLogger)
    {
        $this->logger = $eventLogger;
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();

        $context = [
            'message' => $this->getClassName($message),
            'content' => (string)$message,
            'class' => get_class($message),
        ];

        [$envelope, $exception] = $this->passMessageToNextHandler($envelope, $stack);

        $messageTemplate = $this->prepareMessageTemplate($envelope, $exception);

        $this->logMessageAndPassException($messageTemplate, $context, $exception);

        return $envelope;
    }

    private function getClassName($message): string
    {
        $fullName = get_class($message);

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

        if (null !== $exception) {
            if ($exception->getPrevious()) {
                $logMessage .= sprintf(
                    ' {message} failed because %s (%s). {content}',
                    $this->getClassName($exception->getPrevious()),
                    $exception->getPrevious()
                        ->getMessage()
                );
            } else {
                $logMessage .= sprintf(
                    ' {message} failed because %s (%s). {content}',
                    $this->getClassName($exception),
                    $exception->getMessage()
                );
            }

            return $logMessage;
        }

        $logMessage .= ' {message} {content}';

        return $logMessage;
    }

    private function logMessageAndPassException(string $logMessage, array $context, ?Throwable $exception): void
    {
        if ($exception) {
            $this->logger->error($logMessage, $context);
            throw $exception;
        }

        $this->logger->info($logMessage, $context);
    }
}
