<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Tests\Domain\ValueObject;

use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\CategoryTree\Domain\ValueObject\Node;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class NodeTest extends TestCase
{
    /**
     * @var CategoryId|MockObject
     */
    private $categoryId;

    /**
     */
    protected function setUp()
    {
        $this->categoryId = $this->createMock(CategoryId::class);
    }

    /**
     */
    public function testCreateNode(): void
    {
        $node = new Node($this->categoryId);
        $this->assertEquals($this->categoryId, $node->getCategoryId());
    }

    /**
     */
    public function testSettingParent(): void
    {
        /** @var Node|MockObject $parent */
        $parent = $this->createMock(Node::class);
        $node = new Node($this->categoryId);
        $node->setParent($parent);
        $this->assertEquals($parent, $node->getParent());
    }

    public function testAddChildren():void
    {
        /** @var Node|MockObject $children */
        $children = $this->createMock(Node::class);
        $node = new Node($this->categoryId);
        $node->addChildren($children);
        $this->assertEquals($children, $node->getChildrens()[0]);
    }
}
