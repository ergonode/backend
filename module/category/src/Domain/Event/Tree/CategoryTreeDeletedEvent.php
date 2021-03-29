<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Domain\Event\Tree;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;

use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;

class CategoryTreeDeletedEvent extends AbstractDeleteEvent
{
    private CategoryTreeId $id;

    public function __construct(CategoryTreeId $id)
    {
        $this->id = $id;
    }

    public function getAggregateId(): CategoryTreeId
    {
        return $this->id;
    }
}
