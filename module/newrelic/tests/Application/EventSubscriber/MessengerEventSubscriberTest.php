<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\NewRelic\Tests\Application\EventSubscriber;

use Ergonode\NewRelic\Application\EventSubscriber\MessengerEventSubscriber;
use Ergonode\NewRelic\Application\NewRelic\NewRelicInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Event\WorkerMessageHandledEvent;
use Symfony\Component\Messenger\Event\WorkerMessageReceivedEvent;

class MessengerEventSubscriberTest extends TestCase
{
    public static ?string $mockSapiName = null;

    /**
     * @var NewRelicInterface|MockObject
     */
    private $mockNewRelic;
    private MessengerEventSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->mockNewRelic = $this->createMock(NewRelicInterface::class);

        $this->subscriber = new MessengerEventSubscriber(
            $this->mockNewRelic,
        );
    }

    public function testShouldStartTransaction(): void
    {
        $this->mockNewRelic->expects($this->once())->method('endTransaction');
        $this->mockNewRelic->expects($this->once())->method('startTransaction');
        $this->mockNewRelic->expects($this->once())->method('nameTransaction');

        $this->subscriber->onMessageReceived(
            new WorkerMessageReceivedEvent(
                new Envelope(new \stdClass()),
                'handler_name',
            ),
        );
    }

    public function testShouldEndTransaction(): void
    {
        $this->mockNewRelic->expects($this->once())->method('endTransaction');

        $this->subscriber->onMessageFinished(
            new WorkerMessageHandledEvent(
                new Envelope(new \stdClass()),
                'handler_name',
            ),
        );
    }
}
