<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Tests\Domain\Event;

use Ergonode\Product\Domain\Event\ProductVersionIncreasedEvent;
use PHPUnit\Framework\TestCase;
use Ergonode\Product\Domain\Entity\ProductId;
use PHPUnit\Framework\MockObject\MockObject;

/**
 */
class ProductVersionIncreasedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var ProductId|MockObject $id */
        $id = $this->createMock(ProductId::class);
        $from = 1;
        $to = 2;
        $event = new ProductVersionIncreasedEvent($id, $from, $to);
        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($from, $event->getFrom());
        $this->assertEquals($to, $event->getTo());
    }
}
