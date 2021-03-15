<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\Event\AttributeGroupRemovedEvent;
use PHPUnit\Framework\TestCase;

class AttributeGroupRemovedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var AttributeId $id */
        $id = $this->createMock(AttributeId::class);
        /** @var AttributeGroupId $groupId */
        $groupId = $this->createMock(AttributeGroupId::class);
        $event = new AttributeGroupRemovedEvent($id, $groupId);
        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($groupId, $event->getGroupId());
    }
}
