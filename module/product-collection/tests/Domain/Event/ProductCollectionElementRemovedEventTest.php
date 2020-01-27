<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Tests\Domain\Event;

use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionId;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionElementRemovedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class ProductCollectionElementRemovedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var ProductCollectionId | MockObject $id */
        $id = $this->createMock(ProductCollectionId::class);

        /** @var ProductId | MockObject $productId */
        $productId = $this->createMock(ProductId::class);

        $event = new ProductCollectionElementRemovedEvent($id, $productId);

        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($productId, $event->getProductId());
    }
}
