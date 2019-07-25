<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Tests\Domain\Event;

use Ergonode\CategoryTree\Domain\Entity\CategoryTreeId;
use Ergonode\CategoryTree\Domain\Event\CategoryTreeCreatedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CategoryTreeCreatedEventTest extends TestCase
{
    /**
     */
    public function testCreateEvent(): void
    {
        /** @var CategoryTreeId|MockObject $id */
        $id = $this->createMock(CategoryTreeId::class);
        $name = 'Any tree name';
        $event = new CategoryTreeCreatedEvent($id, $name);
        $this->assertEquals($id, $event->getId());
        $this->assertEquals($name, $event->getName());
    }
}
