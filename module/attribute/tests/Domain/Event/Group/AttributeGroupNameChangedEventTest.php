<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\Event\Group;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\Attribute\Domain\Event\Group\AttributeGroupNameChangedEvent;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AttributeGroupNameChangedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var AttributeGroupId | MockObject $id */
        $id = $this->createMock(AttributeGroupId::class);

        /** @var TranslatableString | MockObject $to */
        $to = $this->createMock(TranslatableString::class);

        $event = new AttributeGroupNameChangedEvent($id, $to);
        $this->assertSame($id, $event->getAggregateId());
        $this->assertSame($to, $event->getTo());
    }
}
