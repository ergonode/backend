<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Tests\Domain\Event;

use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\CategoryTree\Domain\Event\CategoryTreeCategoryRemovedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CategoryTreeCategoryRemovedEventTest extends TestCase
{
    /**
     */
    public function testCreateEvent(): void
    {
        /** @var CategoryId|MockObject $categoryId */
        $categoryId = $this->createMock(CategoryId::class);
        $event = new CategoryTreeCategoryRemovedEvent($categoryId);
        $this->assertEquals($categoryId, $event->getId());
    }
}
