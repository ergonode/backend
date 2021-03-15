<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\Designer\Domain\Event\TemplateImageChangedEvent;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TemplateImageChangedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var TemplateId | MockObject $id */
        $id = $this->createMock(TemplateId::class);

        /** @var MultimediaId | MockObject $to */
        $to = $this->createMock(MultimediaId::class);

        $event = new TemplateImageChangedEvent($id, $to);

        $this->assertSame($id, $event->getAggregateId());
        $this->assertSame($to, $event->getTo());
    }
}
