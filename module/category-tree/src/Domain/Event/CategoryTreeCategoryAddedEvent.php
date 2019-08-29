<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Domain\Event;

use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CategoryTreeCategoryAddedEvent implements DomainEventInterface
{
    /**
     * @var CategoryId
     *
     * @JMS\Type("Ergonode\Category\Domain\Entity\CategoryId")
     */
    private $id;

    /**
     * @var CategoryId|null
     *
     * @JMS\Type("Ergonode\Category\Domain\Entity\CategoryId")
     */
    private $parentId;

    /**
     * @param CategoryId      $id
     * @param CategoryId|null $parentId
     */
    public function __construct(CategoryId $id, ?CategoryId $parentId = null)
    {
        $this->id = $id;
        $this->parentId = $parentId;
    }

    /**
     * @return CategoryId
     */
    public function getId(): CategoryId
    {
        return $this->id;
    }

    /**
     * @return CategoryId|null
     */
    public function getParentId(): ?CategoryId
    {
        return $this->parentId;
    }
}
