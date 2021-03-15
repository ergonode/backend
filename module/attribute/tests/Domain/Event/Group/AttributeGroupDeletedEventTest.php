<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\Event\Group;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\Attribute\Domain\Event\Group\AttributeGroupDeletedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AttributeGroupDeletedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var AttributeGroupId | MockObject $id */
        $id = $this->createMock(AttributeGroupId::class);

        $event = new AttributeGroupDeletedEvent($id);

        $this->assertSame($id, $event->getAggregateId());
    }
}
