<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Domain\Event;

use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\BatchAction\Domain\Event\BatchActionEndedEvent;
use PHPUnit\Framework\TestCase;

class BatchActionEndedEventTest extends TestCase
{
    public function testCreation(): void
    {
        $batchActionId = $this->createMock(BatchActionId::class);

        $event = new BatchActionEndedEvent($batchActionId);

        self::assertEquals($batchActionId, $event->getId());
    }
}
