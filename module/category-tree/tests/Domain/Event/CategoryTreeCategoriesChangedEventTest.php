<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Tests\Domain\Event;

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

    protected function setUp()
    {
        $this->node = $this->createMock(Node::class);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateWithEmptyArray(): void
    {
       new CategoryTreeCategoriesChangedEvent([]);

    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateWithIncorrectTypeInserted(): void
    {
        new CategoryTreeCategoriesChangedEvent([new \stdClass()]);
    }

    /**
     */
    public function testCreateWithCorrectTypeInserted(): void
    {
        $collection = [
            $this->createMock(Node::class),
            $this->createMock(Node::class)
        ];
        $result = new CategoryTreeCategoriesChangedEvent($collection);
        $this->assertEquals($collection, $result->getCategories());

    }
}
