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
class CreateTreeCommand
{
    /**
     * @var CategoryTreeId
     *
     * @JMS\Type("Ergonode\CategoryTree\Domain\Entity\CategoryTreeId")
     */
    private $id;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private $name;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $code;

    /**
     * @var Node[]
     *
     * @JMS\Type("array<Ergonode\CategoryTree\Domain\ValueObject\Node>")
     */
    private $categories;

    /**
     * CreateTreeCommand constructor.
     *
     * @param TranslatableString $name
     * @param string             $code
     * @param array              $categories
     *
     * @throws \Exception
     */
    public function __construct(TranslatableString $name, string $code, array $categories = [])
    {
        $this->id = CategoryTreeId::fromKey($code);
        $this->name = $name;
        $this->code = $code;
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
     * @return string
     */
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
