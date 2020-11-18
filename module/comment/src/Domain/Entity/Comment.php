<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Comment\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Comment\Domain\Event\CommentContentChangedEvent;
use Ergonode\Comment\Domain\Event\CommentCreatedEvent;
use Ergonode\SharedKernel\Domain\Aggregate\CommentId;
use JMS\Serializer\Annotation as JMS;
use Ramsey\Uuid\Uuid;

class Comment extends AbstractAggregateRoot
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
     * @JMS\Type("DateTime")
     */
    private \DateTime $createdAt;

    /**
     * @JMS\Type("DateTime")
     */
    private ?\DateTime $editedAt = null;

    /**
     * @JMS\Type("string")
     */
    private string $content;

    /**
     * @throws \Exception
     */
    public function __construct(CommentId $id, Uuid $objectId, UserId $authorId, string $content)
    {
        $this->apply(new CommentCreatedEvent($id, $authorId, $objectId, $content, new \DateTime()));
    }

    /**
     * @throws \Exception
     */
    public function changeContent(string $content): void
    {
        if ($content !== $this->content) {
            $this->apply(new CommentContentChangedEvent($this->id, $content, new \DateTime()));
        }
    }

    public function getId(): CommentId
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

    public function getEditedAt(): ?\DateTime
    {
        return $this->editedAt;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    protected function applyCommentCreatedEvent(CommentCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->authorId = $event->getAuthorId();
        $this->objectId = $event->getObjectId();
        $this->createdAt = $event->getCreatedAt();
        $this->content = $event->getContent();
    }

    protected function applyCommentContentChangedEvent(CommentContentChangedEvent $event): void
    {
        $this->content = $event->getTo();
        $this->editedAt = $event->getEditedAt();
    }
}
