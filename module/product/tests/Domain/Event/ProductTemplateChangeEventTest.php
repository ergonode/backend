<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\Product\Domain\Event\ProductTemplateChangedEvent;

class ProductTemplateChangeEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        $id = $this->createMock(ProductId::class);
        $templateId = $this->createMock(TemplateId::class);
        $event = new ProductTemplateChangedEvent($id, $templateId);
        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($templateId, $event->getTemplateId());
    }
}
