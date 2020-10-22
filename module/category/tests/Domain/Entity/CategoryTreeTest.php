<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Tests\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Category\Domain\Entity\CategoryTree;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\Category\Domain\ValueObject\Node;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CategoryTreeTest extends TestCase
{
    /**
     * @var CategoryTreeId|MockObject
     */
    private $id;

    /**
     * @var string
     */
    private string $code;

    /**
     * @var TranslatableString|MockObject
     */
    private $name;

    protected function setUp(): void
    {
        $this->id = $this->createMock(CategoryTreeId::class);
        $this->code = 'Any correct code';
        $this->name = $this->createMock(TranslatableString::class);
    }

    public function testCreateCategoryTree(): void
    {
        $tree = new CategoryTree($this->id, $this->code, $this->name);
        self::assertEquals($this->id, $tree->getId());
        self::assertEquals($this->code, $tree->getCode());
        self::assertEquals($this->name, $tree->getName());
    }

    public function testAddingCategory(): void
    {
        $root = CategoryId::generate();
        $category1 = CategoryId::generate();
        $category2 = CategoryId::generate();

        $tree = new CategoryTree($this->id, $this->code, $this->name);
        $tree->addCategory($root);
        self::assertTrue($tree->hasCategory($root));
        $tree->addCategory($category1, $root);
        self::assertTrue($tree->hasCategory($category1));
        $tree->addCategory($category2, $category1);
        self::assertTrue($tree->hasCategory($category2));
    }

    public function testAddingExistingCategory(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $category = CategoryId::generate();

        $tree = new CategoryTree($this->id, $this->code, $this->name);

        $tree->addCategory($category);
        $tree->addCategory($category);
    }

    public function testRenamingCategory(): void
    {
        $tree = new CategoryTree($this->id, $this->code, $this->name);

        $name2 = new TranslatableString(['en_GB' => 'english']);
        $tree->changeName($name2);
        self::assertEquals($name2, $tree->getName());
    }

    public function testUpdatingCategories(): void
    {
        $categoryId = CategoryId::generate();
        $node = new Node($categoryId);
        $categories = [$node];

        $tree = new CategoryTree($this->id, $this->code, $this->name);

        $tree->updateCategories($categories);

        self::assertTrue($tree->hasCategory($categoryId));
    }
}
