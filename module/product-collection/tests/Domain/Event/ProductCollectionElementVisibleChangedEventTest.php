<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionElementVisibleChangedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductCollectionElementVisibleChangedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var ProductCollectionId |MockObject $id */
        $id = $this->createMock(ProductCollectionId::class);

        /** @var ProductId | MockObject $productId */
        $productId = $this->createMock(ProductId::class);

        $event = new ProductCollectionElementVisibleChangedEvent($id, $productId, true);

        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($productId, $event->getProductId());
        $this->assertTrue($event->isVisible());
    }
}
