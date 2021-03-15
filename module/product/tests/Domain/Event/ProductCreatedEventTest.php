<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Domain\Event;

use Ergonode\Product\Domain\Event\ProductCreatedEvent;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

class ProductCreatedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var ProductId|MockObject $id */
        $id = $this->createMock(ProductId::class);
        /** @var Sku|MockObject $sku */
        $sku = $this->createMock(Sku::class);
        $type = 'simple';
        $categories = ['example1', 'example2'];
        $attributes = ['example1', 'example2'];
        $templateId = $this->createMock(TemplateId::class);
        $event = new ProductCreatedEvent($id, $sku, $type, $templateId, $categories, $attributes);
        $this::assertEquals($id, $event->getAggregateId());
        $this::assertEquals($sku, $event->getSku());
        $this::assertEquals($type, $event->getType());
        $this::assertEquals($templateId, $event->getTemplateId());
        $this::assertEquals($categories, $event->getCategories());
        $this::assertEquals($attributes, $event->getAttributes());
    }
}
