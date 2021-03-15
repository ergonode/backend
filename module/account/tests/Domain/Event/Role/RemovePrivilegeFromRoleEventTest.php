<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Domain\Event\Role;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\Account\Domain\Event\Role\RemovePrivilegeFromRoleEvent;
use Ergonode\Account\Domain\ValueObject\Privilege;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RemovePrivilegeFromRoleEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var RoleId|MockObject $id */
        $id = $this->createMock(RoleId::class);
        /** @var Privilege|MockObject $privilege */
        $privilege = $this->createMock(Privilege::class);
        $event = new RemovePrivilegeFromRoleEvent($id, $privilege);
        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($privilege, $event->getPrivilege());
    }
}
