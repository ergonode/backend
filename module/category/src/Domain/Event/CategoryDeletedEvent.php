<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;

class CategoryDeletedEvent extends AbstractDeleteEvent
{
    private CategoryId $id;

    public function __construct(CategoryId $id)
    {
        $this->id = $id;
    }

    public function getAggregateId(): CategoryId
    {
        return $this->id;
    }
}
