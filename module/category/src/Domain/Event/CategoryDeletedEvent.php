<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Domain\Event;

use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CategoryDeletedEvent extends AbstractDeleteEvent
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
     * @return AbstractId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }
}
