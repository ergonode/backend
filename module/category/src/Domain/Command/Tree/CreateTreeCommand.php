<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Domain\Command\Tree;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Category\Application\Model\Tree\TreeNodeFormModel;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\Category\Domain\ValueObject\Node;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CreateTreeCommand implements DomainCommandInterface
{
    /**
     * @var CategoryTreeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId")
     */
    private CategoryTreeId $id;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $name;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $code;

    /**
     * @var Node[]
     *
     * @JMS\Type("array<Ergonode\Category\Domain\ValueObject\Node>")
     */
    private array $categories;

    /**
     * @param string             $code
     * @param TranslatableString $name
     * @param array              $categories
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
        foreach ($category->children as $child) {
            $child = $this->createNode($child);
            $node->addChild($child);
        }

        return $node;
    }
}
