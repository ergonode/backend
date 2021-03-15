<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\Event\Attribute;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeDeletedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AttributeDeletedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var AttributeId | MockObject $id */
        $id = $this->createMock(AttributeId::class);

        $event = new AttributeDeletedEvent($id);

        $this->assertSame($id, $event->getAggregateId());
    }
}
