<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Category\Domain\Entity\CategoryId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CategoryTreeCategoryRemovedEvent implements DomainEventInterface
{
    /**
     * @var CategoryId
     *
     * @JMS\Type("Ergonode\Category\Domain\Entity\CategoryId")
     */
    private $id;

    /**
     * @param CategoryId $id
     */
    public function __construct(CategoryId $id)
    {
        $this->id = $id;
    }

    /**
     * @return CategoryId
     */
    public function getId(): CategoryId
    {
        return $this->id;
    }
}
