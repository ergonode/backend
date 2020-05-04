<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Tests\Domain\Event;

use Ergonode\Product\Domain\Event\ProductCreatedEvent;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class ProductCreatedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var ProductId|MockObject $id */
        $id = $this->createMock(ProductId::class);
        /** @var Sku|MockObject $sku */
        $sku = $this->createMock(Sku::class);
        $type = 'simple';
        $class = 'class';
        $categories = ['example1', 'example2'];
        $attributes = ['example1', 'example2'];
        $event = new ProductCreatedEvent($id, $sku, $type, $class, $categories, $attributes);
        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($sku, $event->getSku());
        $this->assertEquals($type, $event->getType());
        $this->assertEquals($class, $event->getClass());
        $this->assertEquals($categories, $event->getCategories());
        $this->assertEquals($attributes, $event->getAttributes());
    }
}
