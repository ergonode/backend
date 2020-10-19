<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\Event\Attribute;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeLabelChangedEvent;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\TestCase;

/**
 */
class AttributeLabelChangedEventTest extends TestCase
{
    /**
     * @param AttributeId        $id
     * @param TranslatableString $from
     * @param TranslatableString $to
     *
     * @dataProvider dataProvider
     */
    public function testCreateEvent(AttributeId $id, TranslatableString $from, TranslatableString $to): void
    {
        $event = new AttributeLabelChangedEvent($id, $from, $to);
        self::assertSame($id, $event->getAggregateId());
        self::assertSame($from, $event->getFrom());
        self::assertSame($to, $event->getTo());
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    public function dataProvider(): array
    {
        return [
            [
                $this->createMock(AttributeId::class),
                $this->createMock(TranslatableString::class),
                $this->createMock(TranslatableString::class),
            ],
        ];
    }
}
