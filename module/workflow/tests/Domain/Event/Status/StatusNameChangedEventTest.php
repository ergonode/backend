<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Event\Status;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Workflow\Domain\Entity\StatusId;
use Ergonode\Workflow\Domain\Event\Status\StatusNameChangedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class StatusNameChangedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var StatusId | MockObject $id */
        $id = $this->createMock(StatusId::class);

        /** @var TranslatableString | MockObject $from */
        $from = $this->createMock(TranslatableString::class);

        /** @var TranslatableString | MockObject $to */
        $to = $this->createMock(TranslatableString::class);

        $event = new StatusNameChangedEvent($id, $from, $to);

        $this->assertSame($id, $event->getAggregateId());
        $this->assertSame($from, $event->getFrom());
        $this->assertSame($to, $event->getTo());
    }
}
