<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\Event\AttributeOptionChangedEvent;
use Ergonode\Attribute\Domain\ValueObject\OptionInterface;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use PHPUnit\Framework\TestCase;

/**
 */
class AttributeOptionChangedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var AttributeId $id */
        $id = $this->createMock(AttributeId::class);
        /** @var OptionKey $key */
        $key = $this->createMock(OptionKey::class);
        /** @var OptionInterface $from */
        $from = $this->createMock(OptionInterface::class);
        /** @var OptionInterface $to */
        $to = $this->createMock(OptionInterface::class);
        $event = new AttributeOptionChangedEvent($id, $key, $from, $to);
        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($key, $event->getKey());
        $this->assertEquals($from, $event->getFrom());
        $this->assertEquals($to, $event->getTo());
    }
}
