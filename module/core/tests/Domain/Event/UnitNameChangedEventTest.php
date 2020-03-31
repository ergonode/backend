<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Tests\Domain\Event;

use Ergonode\Core\Domain\Event\UnitNameChangedEvent;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use PHPUnit\Framework\TestCase;

/**
 */
class UnitNameChangedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        $id = $this->createMock(UnitId::class);
        $from = 'name';
        $to = 'to';

        $event = new UnitNameChangedEvent($id, $from, $to);

        $this->assertSame($id, $event->getAggregateId());
        $this->assertSame($from, $event->getFrom());
        $this->assertSame($to, $event->getTo());
    }
}
