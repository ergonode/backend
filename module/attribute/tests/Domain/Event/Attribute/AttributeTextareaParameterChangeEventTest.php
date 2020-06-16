<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\Event\Attribute;

use Ergonode\Attribute\Domain\Event\Attribute\AttributeTextareaParameterChangeEvent;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use PHPUnit\Framework\TestCase;

/**
 */
class AttributeTextareaParameterChangeEventTest extends TestCase
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
        $event = new AttributeTextareaParameterChangeEvent($id, $name, $from, $to);
        $this->assertSame($id, $event->getAggregateId());
        $this->assertSame($name, $event->getName());
        $this->assertSame($from, $event->getFrom());
        $this->assertSame($to, $event->getTo());
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
