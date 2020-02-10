<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\CategoryTree\Domain\Event\CategoryTreeDeletedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CategoryTreeDeletedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var CategoryTreeId | MockObject $id */
        $id = $this->createMock(CategoryTreeId::class);

        $event = new CategoryTreeDeletedEvent($id);

        $this->assertSame($id, $event->getAggregateId());
    }
}
