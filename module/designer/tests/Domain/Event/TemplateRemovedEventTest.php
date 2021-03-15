<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\Designer\Domain\Event\TemplateRemovedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TemplateRemovedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var TemplateId | MockObject $id */
        $id = $this->createMock(TemplateId::class);
        $reason = 'reason';

        $event = new TemplateRemovedEvent($id, $reason);

        $this->assertSame($id, $event->getAggregateId());
        $this->assertSame($reason, $event->getReason());
    }
}
