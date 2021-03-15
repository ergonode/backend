<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Comment\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CommentId;
use JMS\Serializer\Annotation as JMS;
use Ramsey\Uuid\Uuid;

class CommentCreatedEvent implements AggregateEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CommentId")
     */
    private CommentId $id;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\UserId")
     */
    private UserId $authorId;

    /**
     * @JMS\Type("uuid")
     */
    private Uuid $objectId;

    /**
     * @JMS\Type("string")
     */
    private string $content;

    /**
     * @JMS\Type("DateTime")
     */
    private \DateTime $createdAt;

    public function __construct(CommentId $id, UserId $authorId, Uuid $objectId, string $content, \DateTime $createdAt)
    {
        $this->id = $id;
        $this->authorId = $authorId;
        $this->objectId = $objectId;
        $this->content = $content;
        $this->createdAt = $createdAt;
    }

    public function getAggregateId(): CommentId
    {
        return $this->id;
    }

    public function getAuthorId(): UserId
    {
        return $this->authorId;
    }

    public function getObjectId(): Uuid
    {
        return $this->objectId;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
