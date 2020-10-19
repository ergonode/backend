<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Tests\Domain\Event\Tree;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\Category\Domain\Event\Tree\CategoryTreeCategoryAddedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CategoryTreeCategoryAddedEventTest extends TestCase
{
    /**
     */
    public function testCreateEventWithParent(): void
    {
        /** @var CategoryTreeId $id */
        $id = $this->createMock(CategoryTreeId::class);
        /** @var CategoryId|MockObject $categoryId */
        $categoryId = $this->createMock(CategoryId::class);
        /** @var CategoryId|MockObject $parentId */
        $parentId = $this->createMock(CategoryId::class);
        $event = new CategoryTreeCategoryAddedEvent($id, $categoryId, $parentId);
        self::assertEquals($id, $event->getAggregateId());
        self::assertEquals($categoryId, $event->getCategoryId());
        self::assertEquals($parentId, $event->getParentId());
    }

    /**
     */
    public function testCreateEventWithoutParent(): void
    {
        /** @var CategoryTreeId $id */
        $id = $this->createMock(CategoryTreeId::class);
        /** @var CategoryId|MockObject $categoryId */
        $categoryId = $this->createMock(CategoryId::class);
        $event = new CategoryTreeCategoryAddedEvent($id, $categoryId);
        self::assertEquals($id, $event->getAggregateId());
        self::assertEquals($categoryId, $event->getCategoryId());
        self::assertNull($event->getParentId());
    }
}
