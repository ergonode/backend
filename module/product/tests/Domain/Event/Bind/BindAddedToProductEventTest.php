<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Domain\Event\Bind;

use Ergonode\Product\Domain\Event\Bind\BindAddedToProductEvent;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BindAddedToProductEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var ProductId|MockObject $id */
        $id = $this->createMock(ProductId::class);
        /** @var AttributeId|MockObject $attributeId */
        $attributeId = $this->createMock(AttributeId::class);
        $event = new BindAddedToProductEvent($id, $attributeId);
        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($attributeId, $event->getAttributeId());
    }
}
