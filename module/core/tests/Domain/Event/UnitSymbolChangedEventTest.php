<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Domain\Event;

use Ergonode\Core\Domain\Event\UnitSymbolChangedEvent;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use PHPUnit\Framework\TestCase;

class UnitSymbolChangedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        $id = $this->createMock(UnitId::class);
        $to = 'to';

        $event = new UnitSymbolChangedEvent($id, $to);

        $this->assertSame($id, $event->getAggregateId());
        $this->assertSame($to, $event->getTo());
    }
}
