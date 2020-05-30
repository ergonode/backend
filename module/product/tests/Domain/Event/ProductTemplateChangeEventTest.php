<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Tests\Domain\Event;

use Ergonode\Product\Domain\Event\ProductTemplateChangedEvent;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use PHPUnit\Framework\TestCase;

/**
 */
class ProductTemplateChangeEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        $id = $this->createMock(ProductId::class);
        $templateId = $this->createMock(TemplateId::class);
        $event = new ProductTemplateChangedEvent($id, $templateId);
        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($templateId, $event->getTemplateId());
    }
}
