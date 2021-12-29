<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\Event\Attribute;

use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeOptionRemovedEvent;

class AttributeOptionRemovedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var AggregateId $id */
        $optionId = $this->createMock(AggregateId::class);
        /** @var AttributeId $attributeId */
        $attributeId = $this->createMock(AttributeId::class);

        $event = new AttributeOptionRemovedEvent($attributeId, $optionId);
        $this->assertEquals($attributeId, $event->getAggregateId());
        $this->assertEquals($optionId, $event->getOptionId());
    }
}
