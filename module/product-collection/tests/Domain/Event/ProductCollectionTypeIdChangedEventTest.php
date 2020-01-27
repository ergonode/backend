<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Tests\Domain\Event;

use Ergonode\ProductCollection\Domain\Entity\ProductCollectionId;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionTypeId;
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

        $event = new ProductCollectionTypeIdChangedEvent($id, $typeId, $newTypeId);

        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($typeId, $event->getOldTypeId());
        $this->assertEquals($newTypeId, $event->getNewTypeId());
    }
}
