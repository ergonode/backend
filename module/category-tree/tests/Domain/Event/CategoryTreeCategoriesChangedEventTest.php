<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Tests\Domain\Event;

use Ergonode\CategoryTree\Domain\Entity\CategoryTreeId;
use Ergonode\CategoryTree\Domain\Event\CategoryTreeCategoriesChangedEvent;
use Ergonode\CategoryTree\Domain\ValueObject\Node;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CategoryTreeCategoriesChangedEventTest extends TestCase
{
    /**
     * @var Node|MockObject
     */
    private $node;

    /**
     */
    protected function setUp()
    {
        $this->node = $this->createMock(Node::class);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateWithIncorrectTypeInserted(): void
    {
        /** @var CategoryTreeId $id */
        $id = $this->createMock(CategoryTreeId::class);
        new CategoryTreeCategoriesChangedEvent($id, [new \stdClass()]);
    }

    /**
     */
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
