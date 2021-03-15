<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Domain\Event;

use Ergonode\Core\Domain\Event\UnitDeletedEvent;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UnitDeletedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var UnitId | MockObject $id */
        $id = $this->createMock(UnitId::class);

        $event = new UnitDeletedEvent($id);

        $this->assertSame($id, $event->getAggregateId());
    }
}
