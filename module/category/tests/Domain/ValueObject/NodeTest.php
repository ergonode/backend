<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Tests\Domain\ValueObject;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Category\Domain\ValueObject\Node;
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

    /**
     */
    public function testAddChildren(): void
    {

        $categoryId2 = new CategoryId('cde02652-70ce-484e-bc9d-3bf61391522d');

        /** @var Node|MockObject $children */
        $children = new Node($categoryId2);
        $node = new Node($this->categoryId);
        $this->assertFalse($node->hasChildren($categoryId2));
        $node->addChildren($children);
        $this->assertEquals($children, $node->getChildrens()[0]);
        $this->assertFalse($node->hasSuccessor($this->categoryId));
        $this->assertTrue($node->hasChildren($categoryId2));
    }
}
