<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Domain\Command\Tree;

use Ergonode\Category\Domain\Command\CategoryCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Category\Application\Model\Tree\TreeNodeFormModel;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\Category\Domain\ValueObject\Node;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

class CreateTreeCommand implements CategoryCommandInterface
{
    private CategoryTreeId $id;

    private TranslatableString $name;

    private string $code;

    /**
     * @var Node[]
     */
    private array $categories;

    /**
     * @param array $categories
     *
     * @throws \Exception
     */
    public function __construct(string $code, TranslatableString $name, array $categories = [])
    {
        $this->id = CategoryTreeId::generate();
        $this->name = $name;
        $this->code = $code;
        foreach ($categories as $category) {
            $this->categories[] = $this->createNode($category);
        }
    }

    public function getId(): CategoryTreeId
    {
        return $this->id;
    }

    public function getName(): TranslatableString
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return Node[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    private function createNode(TreeNodeFormModel $category): Node
    {
        $node = new Node(new CategoryId($category->categoryId));
        foreach ($category->children as $child) {
            $child = $this->createNode($child);
            $node->addChild($child);
        }

        return $node;
    }
}
