<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionTypeIdChangedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class ProductCollectionTypeIdChangedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var ProductCollectionId | MockObject $id */
        $id = $this->createMock(ProductCollectionId::class);

        /** @var ProductCollectionTypeId | MockObject $typeId */
        $typeId = $this->createMock(ProductCollectionTypeId::class);

        /** @var ProductCollectionTypeId | MockObject $newTypeId */
        $newTypeId = $this->createMock(ProductCollectionTypeId::class);

        /** @var \DateTime | MockObject $dateTime */
        $dateTime = $this->createMock(\DateTime::class);

        $event = new ProductCollectionTypeIdChangedEvent($id, $typeId, $newTypeId, $dateTime);

        self::assertEquals($id, $event->getAggregateId());
        self::assertEquals($typeId, $event->getOldTypeId());
        self::assertEquals($newTypeId, $event->getNewTypeId());
        self::assertEquals($dateTime, $event->getEditedAt());
    }
}
