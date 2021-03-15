<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Notification\Tests\Infrastructure\Handler;

use Ergonode\Notification\Domain\Command\MarkNotificationCommand;
use Ergonode\Notification\Domain\Query\NotificationQueryInterface;
use Ergonode\Notification\Infrastructure\Handler\MarkNotificationCommandHandler;
use PHPUnit\Framework\TestCase;

class MarkNotificationCommandHandlerTest extends TestCase
{
    public function testHandling(): void
    {
        $query = $this->createMock(NotificationQueryInterface::class);
        $query->expects($this->once())->method('mark');
        $command = $this->createMock(MarkNotificationCommand::class);

        $handler = new MarkNotificationCommandHandler($query);
        $handler->__invoke($command);
    }
}
