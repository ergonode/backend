<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Tests\Domain\Event\Tree;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\Category\Domain\Event\Tree\CategoryTreeCategoriesChangedEvent;
use Ergonode\Category\Domain\ValueObject\Node;
use PHPUnit\Framework\TestCase;

class CategoryTreeCategoriesChangedEventTest extends TestCase
{
    public function testCreateWithIncorrectTypeInserted(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        /** @var CategoryTreeId $id */
        $id = $this->createMock(CategoryTreeId::class);
        new CategoryTreeCategoriesChangedEvent($id, [new \stdClass()]);
    }

    public function testCreateWithCorrectTypeInserted(): void
    {
        /** @var CategoryTreeId $id */
        $id = $this->createMock(CategoryTreeId::class);
        $collection = [
            $this->createMock(Node::class),
            $this->createMock(Node::class),
        ];
        $result = new CategoryTreeCategoriesChangedEvent($id, $collection);
        $this->assertEquals($id, $result->getAggregateId());
        $this->assertEquals($collection, $result->getCategories());
    }
}
