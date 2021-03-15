<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Tests\Domain\Event\Group;

use Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId;
use Ergonode\Designer\Domain\Event\Group\TemplateGroupCreatedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TemplateGroupCreatedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var TemplateGroupId | MockObject $id */
        $id = $this->createMock(TemplateGroupId::class);
        $name = 'name';

        $event = new TemplateGroupCreatedEvent($id, $name);

        $this->assertSame($id, $event->getAggregateId());
        $this->assertSame($name, $event->getName());
    }
}
