<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Notification\Tests\Infrastructure\Sender;

use Ergonode\Notification\Infrastructure\Sender\NotificationSender;
use Ergonode\Notification\Infrastructure\Sender\NotificationStrategyInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class NotificationSenderTest extends TestCase
{

    /**
     * @doesNotPerformAssertions
     */
    public function testSendNotificationWithoutStrategy(): void
    {
        $recipients = [];
        $message = 'Any message';

        $sender = new NotificationSender();
        $sender->send($recipients, $message);
    }

    /**
     */
    public function testSendNotificationWithStrategy(): void
    {
        $recipients = [];
        $message = 'Any message';
        /** @var NotificationStrategyInterface|MockObject $strategy1 */
        $strategy1 = $this->createMock(NotificationStrategyInterface::class);
        $strategy1->expects($this->once())->method('send');
        /** @var NotificationStrategyInterface|MockObject $strategy2 */
        $strategy2 = $this->createMock(NotificationStrategyInterface::class);
        $strategy2->expects($this->once())->method('send');

        $sender = new NotificationSender($strategy1, $strategy2);
        $sender->send($recipients, $message);
    }
}
