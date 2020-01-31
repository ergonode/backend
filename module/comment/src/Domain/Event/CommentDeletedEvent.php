<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Domain\Event;

use Ergonode\Comment\Domain\Entity\CommentId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CommentDeletedEvent extends AbstractDeleteEvent
{
    /**
     * @var CommentId $id
     *
     * @JMS\Type("Ergonode\Comment\Domain\Entity\CommentId")
     */
    private CommentId $id;

    /**
     * @param CommentId $id
     */
    public function __construct(CommentId $id)
    {
        $this->id = $id;
    }

    /**
     * @return AbstractId|CommentId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }
}
