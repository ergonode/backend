<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionTypeIdChangedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductCollectionTypeIdChangedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var ProductCollectionId | MockObject $id */
        $id = $this->createMock(ProductCollectionId::class);

        /** @var ProductCollectionTypeId | MockObject $newTypeId */
        $newTypeId = $this->createMock(ProductCollectionTypeId::class);

        /** @var \DateTime | MockObject $dateTime */
        $dateTime = $this->createMock(\DateTime::class);

        $event = new ProductCollectionTypeIdChangedEvent($id, $newTypeId, $dateTime);

        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($newTypeId, $event->getNewTypeId());
        $this->assertEquals($dateTime, $event->getEditedAt());
    }
}
