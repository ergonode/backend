<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\Event\Attribute;

use Ergonode\Attribute\Domain\Event\Attribute\AttributeBoolParameterChangeEvent;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use PHPUnit\Framework\TestCase;

/**
 */
class AttributeBoolParameterChangeEventTest extends TestCase
{
    /**
     * @param AttributeId $id
     * @param string      $name
     * @param bool        $from
     * @param bool        $to
     *
     * @dataProvider dataProvider
     */
    public function testCreateEvent(AttributeId $id, string $name, bool $from, bool $to): void
    {
        $event = new AttributeBoolParameterChangeEvent($id, $name, $from, $to);
        self::assertSame($id, $event->getAggregateId());
        self::assertSame($name, $event->getName());
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
                'name',
                true,
                false,
            ],
        ];
    }
}
