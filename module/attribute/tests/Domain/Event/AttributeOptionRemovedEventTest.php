<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\Event;

use Ergonode\Attribute\Domain\Event\AttributeOptionRemovedEvent;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use PHPUnit\Framework\TestCase;

/**
 */
class AttributeOptionRemovedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var OptionKey $key */
        $key = $this->createMock(OptionKey::class);
        $event = new AttributeOptionRemovedEvent($key);
        $this->assertEquals($key, $event->getKey());
    }
}
