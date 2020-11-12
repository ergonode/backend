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
use JMS\Serializer\Annotation as JMS;

class UpdateTreeCommand implements CategoryCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId")
     */
    private CategoryTreeId $id;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $name;

    /**
     * @var Node[]
     *
     * @JMS\Type("array<Ergonode\Category\Domain\ValueObject\Node>")
     */
    private array $categories;

    /**
     * @param array $categories
     */
    public function __construct(CategoryTreeId $id, TranslatableString $name, array $categories = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->categories = [];
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
