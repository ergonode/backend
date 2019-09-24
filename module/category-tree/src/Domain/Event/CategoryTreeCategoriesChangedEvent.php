<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Domain\Event;

use Ergonode\CategoryTree\Domain\ValueObject\Node;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class CategoryTreeCategoriesChangedEvent implements DomainEventInterface
{
    /**
     * @var Node[]
     *
     * @JMS\Type("array<Ergonode\CategoryTree\Domain\ValueObject\Node>")
     */
    private $categories;

    /**
     * @param Node[] $categories
     */
    public function __construct(array $categories = [])
    {
        Assert::allIsInstanceOf($categories, Node::class);

        $this->categories = $categories;
    }

    /**
     * @return Node[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }
}
