<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\Designer\Domain\Event\TemplateGroupChangedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class TemplateGroupChangedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var TemplateId | MockObject $id */
        $id = $this->createMock(TemplateId::class);

        /** @var TemplateGroupId | MockObject $from */
        $from = $this->createMock(TemplateGroupId::class);

        /** @var TemplateGroupId | MockObject $to */
        $to = $this->createMock(TemplateGroupId::class);

        $event = new TemplateGroupChangedEvent($id, $from, $to);

        self::assertSame($id, $event->getAggregateId());
        self::assertSame($from, $event->getOld());
        self::assertSame($to, $event->getNew());
    }
}
