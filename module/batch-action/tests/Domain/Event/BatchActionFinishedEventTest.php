<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Domain\Event;

use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\BatchAction\Domain\Event\BatchActionFinishedEvent;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use PHPUnit\Framework\TestCase;

class BatchActionFinishedEventTest extends TestCase
{
    public function testCreation(): void
    {
        $batchActionId = $this->createMock(BatchActionId::class);
        $type = $this->createMock(BatchActionType::class);

        $event = new BatchActionFinishedEvent($batchActionId, $type);

        self::assertEquals($batchActionId, $event->getId());
        self::assertEquals($type, $event->getType());
    }
}
