<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Notification\Tests\Infrastructure\Handler;

use Ergonode\Notification\Domain\Command\MarkAllNotificationsCommand;
use Ergonode\Notification\Domain\Query\NotificationQueryInterface;
use Ergonode\Notification\Infrastructure\Handler\MarkAllNotificationsCommandHandler;
use PHPUnit\Framework\TestCase;

class MarkAllNotificationsCommandHandlerTest extends TestCase
{
    public function testHandling(): void
    {
        $query = $this->createMock(NotificationQueryInterface::class);
        $query->expects(self::once())->method('markAll');
        $command = $this->createMock(MarkAllNotificationsCommand::class);

        $handler = new MarkAllNotificationsCommandHandler($query);
        $handler($command);
    }
}
