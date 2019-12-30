<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Tests\Domain\Event;

use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\Product\Domain\Event\ProductCreatedEvent;
use Ergonode\Product\Domain\ValueObject\Sku;
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
        /** @var TemplateId|MockObject $templateId */
        $categories = ['example1', 'example2'];
        $attributes = ['example1', 'example2'];
        $event = new ProductCreatedEvent($id, $sku, $categories, $attributes);
        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($sku, $event->getSku());
        $this->assertEquals($categories, $event->getCategories());
        $this->assertEquals($attributes, $event->getAttributes());
    }
}
