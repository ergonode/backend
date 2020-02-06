<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Tests\Domain\Event;

use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\CategoryTree\Domain\Event\CategoryTreeCategoryAddedEvent;
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
        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($categoryId, $event->getCategoryId());
        $this->assertEquals($parentId, $event->getParentId());
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
        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($categoryId, $event->getCategoryId());
        $this->assertNull($event->getParentId());
    }
}
