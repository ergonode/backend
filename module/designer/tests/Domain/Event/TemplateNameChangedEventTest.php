<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\Designer\Domain\Event\TemplateNameChangedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TemplateNameChangedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var TemplateId | MockObject $id */
        $id = $this->createMock(TemplateId::class);
        $to = 'to';

        $event = new TemplateNameChangedEvent($id, $to);

        $this->assertSame($id, $event->getAggregateId());
        $this->assertSame($to, $event->getTo());
    }
}
