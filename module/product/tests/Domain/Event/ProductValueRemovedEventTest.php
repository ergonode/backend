<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Domain\Event;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Product\Domain\Event\ProductValueRemovedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class ProductValueRemovedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var ProductId|MockObject $id */
        $id = $this->createMock(ProductId::class);
        /** @var AttributeCode|MockObject $code */
        $code = $this->createMock(AttributeCode::class);
        $event = new ProductValueRemovedEvent($id, $code);
        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($code, $event->getAttributeCode());
    }
}
