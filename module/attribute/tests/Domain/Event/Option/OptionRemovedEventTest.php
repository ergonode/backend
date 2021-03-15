<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\Event\Option;

use PHPUnit\Framework\TestCase;
use Ergonode\Attribute\Domain\Event\Option\OptionRemovedEvent;
use Ergonode\SharedKernel\Domain\AggregateId;

class OptionRemovedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var AggregateId $id */
        $id = $this->createMock(AggregateId::class);
        $event = new OptionRemovedEvent($id);
        $this->assertEquals($id, $event->getAggregateId());
    }
}
