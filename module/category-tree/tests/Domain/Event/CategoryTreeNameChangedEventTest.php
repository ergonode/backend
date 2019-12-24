<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Tests\Domain\Event;

use Ergonode\CategoryTree\Domain\Entity\CategoryTreeId;
use Ergonode\CategoryTree\Domain\Event\CategoryTreeNameChangedEvent;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CategoryTreeNameChangedEventTest extends TestCase
{
    /**
     *
     */
    public function testEventCreation(): void
    {
        /** @var CategoryTreeId | MockObject $id */
        $id = $this->createMock(CategoryTreeId::class);

        /** @var TranslatableString | MockObject $from */
        $from = $this->createMock(TranslatableString::class);

        /** @var TranslatableString | MockObject $to */
        $to = $this->createMock(TranslatableString::class);

        $event = new CategoryTreeNameChangedEvent($id, $from, $to);

        $this->assertSame($id, $event->getAggregateId());
        $this->assertSame($from, $event->getFrom());
        $this->assertSame($to, $event->getTo());
    }
}
