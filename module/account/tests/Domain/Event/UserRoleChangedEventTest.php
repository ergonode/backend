<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Tests\Domain\Event;

use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Account\Domain\Event\UserRoleChangedEvent;
use PHPUnit\Framework\TestCase;

/**
 */
class UserRoleChangedEventTest extends TestCase
{
    /**
     */
    public function testCreateEvent(): void
    {
        $from = $this->createMock(RoleId::class);
        $to = $this->createMock(RoleId::class);

        $event = new UserRoleChangedEvent($from, $to);

        $this->assertEquals($from, $event->getFrom());
        $this->assertEquals($to, $event->getTo());
    }
}
