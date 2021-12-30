<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\EventListener;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

class ConnectionWorkerEventSubscriber implements EventSubscriberInterface
{
    private Connection $connection;
    private LoggerInterface $logger;

    public function __construct(
        Connection $connection,
        LoggerInterface $logger
    ) {
        $this->connection = $connection;
        $this->logger = $logger;
    }

    public function onMessageFailed(WorkerMessageFailedEvent $event): void
    {
        $exception = $event->getThrowable();
        if ($exception instanceof DBALException) {
            $this->handleDBALException($exception);

            return;
        }
        if ($event->getThrowable() instanceof HandlerFailedException) {
            foreach ($event->getThrowable()->getNestedExceptions() as $exception) {
                if ($exception instanceof DBALException) {
                    $this->handleDBALException($exception);

                    return;
                }
            }
        }

        $this->logger->debug(
            'Skipping reconnection.',
            [
                'exception' => $exception,
            ],
        );
    }

    /**
     * @return array[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            WorkerMessageFailedEvent::class => ['onMessageFailed', 100],
        ];
    }

    private function handleDBALException(DBALException $exception): void
    {
        if ($this->connection->isConnected() && $this->connection->ping()) {
            return;
        }
        $this->logger->info(
            'Connection lost. Trying to reconnect.',
            [
                'exception' => $exception,
            ],
        );

        if (!$this->reconnect() || $this->connection->ping()) {
            return;
        }

        $this->logger->critical('Failed to ping the server though reconnecting raised no issue.');
    }

    private function reconnect(): bool
    {
        $this->connection->close();
        try {
            $this->connection->connect();
        } catch (DBALException $exception) {
            $this->logger->error(
                "Failed to reconnect. {$exception->getMessage()}",
                [
                    'exception' => $exception,
                ],
            );

            return false;
        }

        return true;
    }
}
