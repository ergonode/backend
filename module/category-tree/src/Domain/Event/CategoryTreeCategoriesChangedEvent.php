<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Domain\Event;

use Ergonode\CategoryTree\Domain\Entity\CategoryTreeId;
use Ergonode\CategoryTree\Domain\ValueObject\Node;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class CategoryTreeCategoriesChangedEvent implements DomainEventInterface
{
    /**
     * @var CategoryTreeId
     *
     * @JMS\Type("Ergonode\CategoryTree\Domain\Entity\CategoryTreeId")
     */
    private $id;

    /**
     * @var Node[]
     *
     * @JMS\Type("array<Ergonode\CategoryTree\Domain\ValueObject\Node>")
     */
    private $categories;

    /**
     * @param CategoryTreeId $id
     * @param Node[]         $categories
     */
    public function __construct(CategoryTreeId $id, array $categories = [])
    {
        Assert::allIsInstanceOf($categories, Node::class);
        $this->id = $id;
        $this->categories = $categories;
    }

    /**
     * @return AbstractId|CategoryTreeId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return Node[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }
}
