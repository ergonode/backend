<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Domain\Event\Child;

use Ergonode\Product\Domain\Event\Relation\ChildAddedToProductEvent;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ChildAddedToProductEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var ProductId|MockObject $id */
        $id = $this->createMock(ProductId::class);
        /** @var ProductId|MockObject $productId */
        $productId = $this->createMock(ProductId::class);
        $event = new ChildAddedToProductEvent($id, $productId);
        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($productId, $event->getChildId());
    }
}
