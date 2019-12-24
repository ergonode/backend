<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Event\Status;

use Ergonode\Workflow\Domain\Entity\StatusId;
use Ergonode\Workflow\Domain\Event\Status\StatusDeletedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class StatusDeletedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var StatusId | MockObject $id */
        $id = $this->createMock(StatusId::class);

        $event = new StatusDeletedEvent($id);

        $this->assertSame($id, $event->getAggregateId());
    }
}
