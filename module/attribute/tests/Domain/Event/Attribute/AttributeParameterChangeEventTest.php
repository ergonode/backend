<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\Event\Attribute;

use Ergonode\Attribute\Domain\Event\Attribute\AttributeParameterChangeEvent;
use PHPUnit\Framework\TestCase;

/**
 */
class AttributeParameterChangeEventTest extends TestCase
{
    /**
     * @param string $name
     * @param string $from
     * @param string $to
     *
     * @dataProvider dataProvider
     */
    public function testCreateEvent(string $name, string $from, string $to): void
    {
        $event = new AttributeParameterChangeEvent($name, $from, $to);
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
                'name',
                'from',
                'to',
            ],
        ];
    }
}
