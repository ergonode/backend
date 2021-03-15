<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Domain\Event;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Product\Domain\Event\ProductValueChangedEvent;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class ProductValueChangedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var ProductId|MockObject $id */
        $id = $this->createMock(ProductId::class);
        /** @var AttributeCode|MockObject $code */
        $code = $this->createMock(AttributeCode::class);
        /** @var ValueInterface|MockObject $from */
        $from = $this->createMock(ValueInterface::class);
        /** @var ValueInterface|MockObject $to */
        $to = $this->createMock(ValueInterface::class);
        $event = new ProductValueChangedEvent($id, $code, $from, $to);
        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($code, $event->getAttributeCode());
        $this->assertEquals($from, $event->getFrom());
        $this->assertEquals($to, $event->getTo());
    }
}
