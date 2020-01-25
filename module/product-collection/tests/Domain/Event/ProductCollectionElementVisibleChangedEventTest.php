<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Tests\Domain\Event;

use Ergonode\ProductCollection\Domain\Entity\ProductCollectionElementId;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionId;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionElementVisibleChangedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class ProductCollectionElementVisibleChangedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var ProductCollectionId |MockObject $id */
        $id = $this->createMock(ProductCollectionId::class);

        /** @var ProductCollectionElementId | MockObject $elementId */
        $elementId = $this->createMock(ProductCollectionElementId::class);

        $event = new ProductCollectionElementVisibleChangedEvent($id, $elementId, true);

        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($elementId, $event->getElementId());
        $this->assertTrue($event->isNewVisible());
    }
}
