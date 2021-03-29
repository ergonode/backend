<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Comment\Domain\Event;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CommentId;
use Ergonode\SharedKernel\Domain\AggregateId;

class CommentContentChangedEvent implements AggregateEventInterface
{
    private CommentId $id;

    private string $to;

    private \DateTime $editedAt;

    public function __construct(CommentId $id, string $to, \DateTime $editedAt)
    {
        $this->id = $id;
        $this->to = $to;
        $this->editedAt = $editedAt;
    }

    /**
     * @return CommentId|AggregateId
     */
    public function getAggregateId(): AggregateId
    {
        return $this->id;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function getEditedAt(): \DateTime
    {
        return $this->editedAt;
    }
}
