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
use JMS\Serializer\Annotation as JMS;

class CommentDeletedEvent extends AbstractDeleteEvent
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CommentId")
     */
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
