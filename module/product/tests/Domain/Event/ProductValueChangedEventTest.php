<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Tests\Domain\Event;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Product\Domain\Event\ProductValueChangedEvent;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

/**
 */
class ProductValueChangedEventTest extends TestCase
{
    /**
     */
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
        self::assertEquals($id, $event->getAggregateId());
        self::assertEquals($code, $event->getAttributeCode());
        self::assertEquals($from, $event->getFrom());
        self::assertEquals($to, $event->getTo());
    }
}
