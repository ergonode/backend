<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\Event\Attribute;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeLabelChangedEvent;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\TestCase;

class AttributeLabelChangedEventTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testCreateEvent(AttributeId $id, TranslatableString $to): void
    {
        $event = new AttributeLabelChangedEvent($id, $to);
        $this->assertSame($id, $event->getAggregateId());
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
                $this->createMock(TranslatableString::class),
            ],
        ];
    }
}
