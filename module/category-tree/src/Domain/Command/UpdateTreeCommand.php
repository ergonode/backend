<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Domain\Command;

use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\CategoryTree\Application\Model\TreeNodeFormModel;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\CategoryTree\Domain\ValueObject\Node;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class UpdateTreeCommand implements DomainCommandInterface
{
    /**
     * @var CategoryTreeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId")
     */
    private $id;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private $name;

    /**
     * @var Node[]
     *
     * @JMS\Type("array<Ergonode\CategoryTree\Domain\ValueObject\Node>")
     */
    private $categories;

    /**
     * @param CategoryTreeId     $id
     * @param TranslatableString $name
     * @param array              $categories
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


    /**
     * @return CategoryTreeId
     */
    public function getId(): CategoryTreeId
    {
        return $this->id;
    }

    /**
     * @return TranslatableString
     */
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

    /**
     * @param TreeNodeFormModel $category
     *
     * @return Node
     */
    private function createNode(TreeNodeFormModel $category): Node
    {
        $node = new Node(new CategoryId($category->categoryId));
        foreach ($category->childrens as $children) {
            $children = $this->createNode($children);
            $node->addChildren($children);
        }

        return $node;
    }
}
