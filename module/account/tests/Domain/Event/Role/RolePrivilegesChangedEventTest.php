<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Tests\Domain\Event\Role;

use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Account\Domain\Event\Role\RolePrivilegesChangedEvent;
use Ergonode\Account\Domain\ValueObject\Privilege;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class RolePrivilegesChangedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var RoleId|MockObject $id */
        $id = $this->createMock(RoleId::class);
        /** @var Privilege|MockObject $privilege */
        $from = [$this->createMock(Privilege::class)];
        $to = [$this->createMock(Privilege::class)];

        $event = new RolePrivilegesChangedEvent($id, $from, $to);
        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($from, $event->getFrom());
        $this->assertEquals($to, $event->getTo());
    }
}
