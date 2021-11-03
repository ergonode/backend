<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\Event\Attribute;

use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeOptionAddedEvent;

class AttributeOptionAddedEventTest extends TestCase
{
    public function testEventCreationWithOption(): void
    {
        /** @var AggregateId $id */
        $optionId = $this->createMock(AggregateId::class);
        /** @var AttributeId $attributeId */
        $attributeId = $this->createMock(AttributeId::class);
        $position = 1;

        $event = new AttributeOptionAddedEvent($attributeId, $optionId, $position);
        $this->assertEquals($attributeId, $event->getAggregateId());
        $this->assertEquals($optionId, $event->getOptionId());
        $this->assertEquals($position, $event->getPosition());
    }
}
