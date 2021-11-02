<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\Event\Option;

use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\Event\Option\OptionMovedEvent;

class OptionMovedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var AggregateId $id */
        $optionId = $this->createMock(AggregateId::class);
        /** @var AttributeId $attributeId */
        $attributeId = $this->createMock(AttributeId::class);

        $event = new OptionMovedEvent($attributeId, $optionId);
        $this->assertEquals($attributeId, $event->getAggregateId());
        $this->assertEquals($optionId, $event->getOptionId());
        $this->assertTrue($event->isAfter());
    }
}
