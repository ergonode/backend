<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Tests\Domain\Event;

use Ergonode\Category\Domain\Entity\CategoryId;
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
        /** @var CategoryId|MockObject $categoryId */
        $categoryId = $this->createMock(CategoryId::class);
        /** @var CategoryId|MockObject $parentId */
        $parentId = $this->createMock(CategoryId::class);
        $event = new CategoryTreeCategoryAddedEvent($categoryId, $parentId);
        $this->assertEquals($categoryId, $event->getId());
        $this->assertEquals($parentId, $event->getParentId());
    }

    /**
     */
    public function testCreateEventWithoutParent(): void
    {
        /** @var CategoryId|MockObject $categoryId */
        $categoryId = $this->createMock(CategoryId::class);
        $event = new CategoryTreeCategoryAddedEvent($categoryId);
        $this->assertEquals($categoryId, $event->getId());
        $this->assertNull($event->getParentId());
    }
}
