<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Tests\Domain\Event;

use Ergonode\Multimedia\Domain\Event\AvatarDeletedEvent;
use Ergonode\SharedKernel\Domain\Aggregate\AvatarId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class AvatarDeletedEventTest extends TestCase
{
    /**
     */
    public function testCreationEvent(): void
    {
        /** @var AvatarId | MockObject $id */
        $id = $this->createMock(AvatarId::class);

        $event = new AvatarDeletedEvent($id);

        $this->assertSame($id, $event->getAggregateId());
    }
}
