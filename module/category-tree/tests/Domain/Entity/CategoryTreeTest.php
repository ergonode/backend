<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Tests\Domain\Entity;

use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\CategoryTree\Domain\Entity\CategoryTree;
use Ergonode\CategoryTree\Domain\Entity\CategoryTreeId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CategoryTreeTest extends TestCase
{
    /**
     * @var CategoryTreeId|MockObject
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     */
    protected function setUp()
    {
        $this->id = $this->createMock(CategoryTreeId::class);
        $this->name = 'Any correct name';
    }

    /**
     */
    public function testCreateCategoryTree(): void
    {
        $tree = new CategoryTree($this->id, $this->name);
        $this->assertEquals($this->id, $tree->getId());
        $this->assertEquals($this->name, $tree->getName());
    }

    /**
     */
    public function testAddingCategory(): void
    {
        $root = CategoryId::generate();
        $category = CategoryId::generate();

        $tree = new CategoryTree($this->id, $this->name);
        $tree->addCategory($root);
        $this->assertTrue($tree->hasCategory($root));
        $tree->addCategory($category, $root);
        $this->assertTrue($tree->hasCategory($category));
    }
}
