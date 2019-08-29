<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Domain\Command;

use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\CategoryTree\Application\Model\TreeNodeFormModel;
use Ergonode\CategoryTree\Domain\Entity\CategoryTreeId;
use Ergonode\CategoryTree\Domain\ValueObject\Node;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use JMS\Serializer\Annotation as JMS;

/**
 */
class UpdateTreeCommand
{
    /**
     * @var CategoryTreeId
     *
     * @JMS\Type("Ergonode\CategoryTree\Domain\Entity\CategoryTreeId")
     */
    private $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $code;

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
     * @param string             $code
     * @param TranslatableString $name
     * @param array              $categories
     */
    public function __construct(CategoryTreeId $id, string $code, TranslatableString $name, array $categories)
    {
        $this->id = $id;
        $this->code = $code;
        $this->name = $name;
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
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
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
