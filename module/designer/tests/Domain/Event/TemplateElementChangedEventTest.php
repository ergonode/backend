<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\Designer\Domain\Event\TemplateElementChangedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;

class TemplateElementChangedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var TemplateId | MockObject $id */
        $id = $this->createMock(TemplateId::class);

        /** @var TemplateElementInterface | MockObject $element */
        $element = $this->createMock(TemplateElementInterface::class);

        $event = new TemplateElementChangedEvent($id, $element);

        $this->assertSame($id, $event->getAggregateId());
        $this->assertSame($element, $event->getElement());
    }
}
