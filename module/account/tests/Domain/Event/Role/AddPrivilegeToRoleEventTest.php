<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Tests\Domain\Event\Role;

use Ergonode\Account\Domain\Event\Role\AddPrivilegeToRoleEvent;
use Ergonode\Account\Domain\ValueObject\Privilege;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class AddPrivilegeToRoleEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var Privilege|MockObject $privilege */
        $privilege = $this->createMock(Privilege::class);
        $event = new AddPrivilegeToRoleEvent($privilege);
        $this->assertEquals($privilege, $event->getPrivilege());
    }
}
