<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Infrastructure\EventListener;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Core\Infrastructure\EventListener\ConnectionWorkerEventSubscriber;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

class ConnectionWorkerEventSubscriberTest extends TestCase
{
    /**
     * @var Connection|MockObject
     */
    private $mockConnection;

    /**
     * @var LoggerInterface|MockObject
     */
    private $mockLogger;

    private ConnectionWorkerEventSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->mockConnection = $this->createMock(Connection::class);
        $this->mockLogger = $this->createMock(LoggerInterface::class);

        $this->subscriber = new ConnectionWorkerEventSubscriber(
            $this->mockConnection,
            $this->mockLogger,
        );
    }

    public function testShouldReconnectOnDBALException(): void
    {
        $this->mockConnection->method('isConnected')->willReturn(true);
        $this->mockConnection->method('ping')->willReturnOnConsecutiveCalls(false, true);
        $this->mockConnection->expects($this->once())->method('close');
        $this->mockConnection->expects($this->once())->method('connect');
        $this->mockLogger->expects($this->never())->method('critical');

        $this->subscriber->onMessageFailed(
            new WorkerMessageFailedEvent(
                new Envelope(new \stdClass()),
                'transport',
                new DBALException(),
            ),
        );
    }

    public function testShouldReconnectOnHandlerException(): void
    {
        $this->mockConnection->method('isConnected')->willReturn(true);
        $this->mockConnection->method('ping')->willReturnOnConsecutiveCalls(false, true);
        $this->mockConnection->expects($this->once())->method('close');
        $this->mockConnection->expects($this->once())->method('connect');
        $this->mockLogger->expects($this->never())->method('critical');

        $this->subscriber->onMessageFailed(
            new WorkerMessageFailedEvent(
                new Envelope(new \stdClass()),
                'transport',
                new HandlerFailedException(
                    new Envelope(new \stdClass()),
                    [new DBALException()],
                ),
            ),
        );
    }

    public function testShouldLogErrorWhenReconnectingFailedOnDBALException(): void
    {
        $this->mockConnection->method('isConnected')->willReturn(true);
        $this->mockConnection->method('ping')->willReturn(false);
        $this->mockConnection->expects($this->once())->method('close');
        $this->mockConnection->expects($this->once())->method('connect');
        $this->mockLogger->expects($this->once())->method('critical');

        $this->subscriber->onMessageFailed(
            new WorkerMessageFailedEvent(
                new Envelope(new \stdClass()),
                'transport',
                new DBALException(),
            ),
        );
    }

    public function testShouldLogErrorWhenReconnectingFailedOnHandlerException(): void
    {
        $this->mockConnection->method('isConnected')->willReturn(true);
        $this->mockConnection->method('ping')->willReturn(false);
        $this->mockConnection->expects($this->once())->method('close');
        $this->mockConnection->expects($this->once())->method('connect');
        $this->mockLogger->expects($this->once())->method('critical');

        $this->subscriber->onMessageFailed(
            new WorkerMessageFailedEvent(
                new Envelope(new \stdClass()),
                'transport',
                new HandlerFailedException(
                    new Envelope(new \stdClass()),
                    [new DBALException()],
                ),
            ),
        );
    }

    public function testShouldNotReconnectOnNotDBALException(): void
    {
        $this->mockConnection->method('isConnected')->willReturn(true);
        $this->mockConnection->expects($this->never())->method('ping');
        $this->mockConnection->expects($this->never())->method('connect');

        $this->subscriber->onMessageFailed(
            new WorkerMessageFailedEvent(
                new Envelope(new \stdClass()),
                'transport',
                new \Exception(),
            ),
        );
    }

    public function testShouldNotReconnectWhenConnectionKeptOnDBALException(): void
    {
        $this->mockConnection->method('isConnected')->willReturn(true);
        $this->mockConnection->method('ping')->willReturn(true);
        $this->mockConnection->expects($this->never())->method('connect');

        $this->subscriber->onMessageFailed(
            new WorkerMessageFailedEvent(
                new Envelope(new \stdClass()),
                'transport',
                new DBALException(),
            ),
        );
    }
}
