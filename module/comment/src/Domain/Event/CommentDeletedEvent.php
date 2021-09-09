<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Comment\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use Ergonode\SharedKernel\Domain\Aggregate\CommentId;
use Ergonode\SharedKernel\Domain\AggregateId;

class CommentDeletedEvent extends AbstractDeleteEvent
{
    private CommentId $id;

    public function __construct(CommentId $id)
    {
        $this->id = $id;
    }

    /**
     * @return AggregateId|CommentId
     */
    public function getAggregateId(): AggregateId
    {
        return $this->id;
    }
}
