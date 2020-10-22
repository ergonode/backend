<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\EventListener;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

class ConnectionWorkerEventSubscriber implements EventSubscriberInterface
{
    private Connection  $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws \Throwable
     */
    public function onMessageFailed(WorkerMessageFailedEvent $event): void
    {
        if ($event->getThrowable() instanceof HandlerFailedException) {
            foreach ($event->getThrowable()->getNestedExceptions() as $exception) {
                if ($exception instanceof DBALException) {
                    $this->reconnect();
                    if (!$this->connection->ping()) {
                        throw $event->getThrowable();
                    }
                }
            }
        }
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

    private function reconnect(): void
    {
        if (!$this->connection->ping()) {
            $this->connection->close();
            $this->connection->connect();
        }
    }
}
