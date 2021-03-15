<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Tests\Domain\Event\Tree;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\Category\Domain\Event\Tree\CategoryTreeDeletedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CategoryTreeDeletedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var CategoryTreeId | MockObject $id */
        $id = $this->createMock(CategoryTreeId::class);

        $event = new CategoryTreeDeletedEvent($id);

        $this->assertSame($id, $event->getAggregateId());
    }
}
