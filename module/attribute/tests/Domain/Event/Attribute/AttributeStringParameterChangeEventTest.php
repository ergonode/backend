<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\Event\Attribute;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeStringParameterChangeEvent;
use PHPUnit\Framework\TestCase;

class AttributeStringParameterChangeEventTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testCreateEvent(AttributeId $id, string $name, string $to): void
    {
        $event = new AttributeStringParameterChangeEvent($id, $name, $to);
        $this->assertSame($id, $event->getAggregateId());
        $this->assertSame($name, $event->getName());
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
                'to',
            ],
        ];
    }
}
