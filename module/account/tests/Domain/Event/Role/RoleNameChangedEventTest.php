<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Domain\Event\Role;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\Account\Domain\Event\Role\RoleNameChangedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RoleNameChangedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var RoleId|MockObject $id */
        $id = $this->createMock(RoleId::class);
        $to = 'New Description';

        $event = new RoleNameChangedEvent($id, $to);
        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($to, $event->getTo());
    }
}
