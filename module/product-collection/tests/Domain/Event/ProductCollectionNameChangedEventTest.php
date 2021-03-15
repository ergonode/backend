<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Tests\Domain\Event;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionNameChangedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductCollectionNameChangedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var ProductCollectionId | MockObject $id */
        $id = $this->createMock(ProductCollectionId::class);

        /** @var TranslatableString | MockObject $to */
        $to = $this->createMock(TranslatableString::class);

        /** @var \DateTime | MockObject $dateTime */
        $dateTime = $this->createMock(\DateTime::class);

        $event = new ProductCollectionNameChangedEvent($id, $to, $dateTime);

        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($to, $event->getTo());
        $this->assertEquals($dateTime, $event->getEditedAt());
    }
}
