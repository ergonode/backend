<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Domain\Event;

use Ergonode\CategoryTree\Domain\Entity\CategoryTreeId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use Ergonode\EventSourcing\Infrastructure\DomainAggregateEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CategoryTreeDeletedEvent extends AbstractDeleteEvent implements DomainAggregateEventInterface
{
    /**
     * @var CategoryTreeId
     *
     * @JMS\Type("Ergonode\CategoryTree\Domain\Entity\CategoryTreeId")
     */
    private $id;

    /**
     * @param CategoryTreeId $id
     */
    public function __construct(CategoryTreeId $id)
    {
        $this->id = $id;
    }

    /**
     * @return AbstractId|CategoryTreeId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }
}
