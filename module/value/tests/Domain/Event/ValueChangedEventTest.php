<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Value\Tests\Domain\Event;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Value\Domain\Event\ValueChangedEvent;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use PHPUnit\Framework\TestCase;

/**
 */
class ValueChangedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var CategoryId $id */
        $id = $this->createMock(CategoryId::class);
        /** @var AttributeCode $code */
        $code = $this->createMock(AttributeCode::class);
        /** @var ValueInterface $from */
        $from = $this->createMock(ValueInterface::class);
        /** @var ValueInterface $to */
        $to = $this->createMock(ValueInterface::class);

        $event = new ValueChangedEvent($id, $code, $from, $to);
        $this->assertSame($id, $event->getAggregateId());
        $this->assertSame($code, $event->getAttributeCode());
        $this->assertSame($from, $event->getFrom());
        $this->assertSame($to, $event->getTo());
    }
}
