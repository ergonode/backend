<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Domain\Event\Status;

use Ergonode\Core\Domain\ValueObject\Color;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Event\Status\StatusColorChangedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class StatusColorChangedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var StatusId | MockObject $id */
        $id = $this->createMock(StatusId::class);

        /** @var Color |MockObject $from */
        $from = $this->createMock(Color::class);

        /** @var Color |MockObject $to */
        $to = $this->createMock(Color::class);

        $event = new StatusColorChangedEvent($id, $from, $to);

        $this->assertSame($id, $event->getAggregateId());
        $this->assertSame($from, $event->getFrom());
        $this->assertSame($to, $event->getTo());
    }
}
