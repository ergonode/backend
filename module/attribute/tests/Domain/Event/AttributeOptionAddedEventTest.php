<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\Event\AttributeOptionAddedEvent;
use Ergonode\Attribute\Domain\ValueObject\OptionInterface;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use PHPUnit\Framework\TestCase;

/**
 */
class AttributeOptionAddedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var AttributeId $id */
        $id = $this->createMock(AttributeId::class);
        /** @var OptionKey $key */
        $key = $this->createMock(OptionKey::class);
        /** @var OptionInterface $option */
        $option = $this->createMock(OptionInterface::class);
        $event = new AttributeOptionAddedEvent($id, $key, $option);
        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($key, $event->getKey());
        $this->assertEquals($option, $event->getOption());
    }
}
