<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Multimedia\Domain\Event\MultimediaDeletedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MultimediaDeletedEventTest extends TestCase
{
    public function testCreationEvent(): void
    {
        /** @var MultimediaId | MockObject $id */
        $id = $this->createMock(MultimediaId::class);

        $event = new MultimediaDeletedEvent($id);

        $this->assertSame($id, $event->getAggregateId());
    }
}
