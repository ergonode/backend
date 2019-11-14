<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Notification\Tests\Infrastructure\Handler;

use Ergonode\Account\Domain\Query\RoleQueryInterface;
use Ergonode\Notification\Domain\Command\SendNotificationCommand;
use Ergonode\Notification\Infrastructure\Handler\SendNotificationCommandHandler;
use Ergonode\Notification\Infrastructure\Sender\NotificationSender;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class SendNotificationCommandHandlerTest extends TestCase
{
    /**
     * @var NotificationSender|MockObject
     */
    private $service;

    /**
     * @var RoleQueryInterface|MockObject
     */
    private $query;

    /**
     * @var SendNotificationCommand|MockObject
     */
    private $command;


    /**
     */
    protected function setUp()
    {
        $this->service = $this->createMock(NotificationSender::class);
        $this->service->expects($this->once())->method('send');
        $this->query = $this->createMock(RoleQueryInterface::class);
        $this->query->expects($this->once())->method('getAllRoleUsers');
        $this->command = $this->createMock(SendNotificationCommand::class);
    }

    /**
     */
    public function testHandling(): void
    {
        $handler = new SendNotificationCommandHandler($this->service, $this->query);
        $handler->__invoke($this->command);
    }
}
