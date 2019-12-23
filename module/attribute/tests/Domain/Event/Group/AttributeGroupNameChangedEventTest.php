<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\Event\Group;

use Ergonode\Attribute\Domain\Event\Group\AttributeGroupNameChangedEvent;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class AttributeGroupNameChangedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var TranslatableString | MockObject $from */
        $from = $this->createMock(TranslatableString::class);

        /** @var TranslatableString | MockObject $to */
        $to = $this->createMock(TranslatableString::class);

        $event = new AttributeGroupNameChangedEvent($from, $to);
        $this->assertSame($from, $event->getFrom());
        $this->assertSame($to, $event->getTo());
    }
}
