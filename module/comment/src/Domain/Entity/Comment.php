<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Domain\Entity;

use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Comment\Domain\Event\CommentContentChangedEvent;
use Ergonode\Comment\Domain\Event\CommentCreatedEvent;
use JMS\Serializer\Annotation as JMS;
use Ramsey\Uuid\Uuid;

/**
 */
class Comment extends AbstractAggregateRoot
{
    /**
     * @var CommentId $id
     *
     * @JMS\Type("Ergonode\Comment\Domain\Entity\CommentId")
     */
    private CommentId $id;

    /**
     * @var UserId $authorId
     *
     * @JMS\Type("Ergonode\Account\Domain\Entity\UserId")
     */
    private UserId $authorId;

    /**
     * @var Uuid
     *
     * @JMS\Type("uuid")
     */
    private Uuid $objectId;

    /**
     * @var \DateTime $createdAt
     *
     * @JMS\Type("DateTime")
     */
    private \DateTime $createdAt;

    /**
     * @var null|\DateTime $editedAt
     *
     * @JMS\Type("DateTime")
     */
    private ?\DateTime $editedAt = null;

    /**
     * @var string $content
     *
     * @JMS\Type("string")
     */
    private string $content;

    /**
     * @param CommentId $id
     * @param Uuid      $objectId
     * @param UserId    $authorId
     * @param string    $content
     *
     * @throws \Exception
     */
    public function __construct(CommentId $id, Uuid $objectId, UserId $authorId, string $content)
    {
        $this->apply(new CommentCreatedEvent($id, $authorId, $objectId, $content, new \DateTime()));
    }

    /**
     * @param string $contend
     *
     * @throws \Exception
     */
    public function changeContent(string $contend): void
    {
        if ($contend !== $this->content) {
            $this->apply(new CommentContentChangedEvent($this->id, $this->content, $contend, new \DateTime()));
        }
    }

    /**
     * @return CommentId
     */
    public function getId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return UserId
     */
    public function getAuthorId(): UserId
    {
        return $this->authorId;
    }

    /**
     * @return Uuid
     */
    public function getObjectId(): Uuid
    {
        return $this->objectId;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getEditedAt(): ?\DateTime
    {
        return $this->editedAt;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param CommentCreatedEvent $event
     */
    protected function applyCommentCreatedEvent(CommentCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->authorId = $event->getAuthorId();
        $this->objectId = $event->getObjectId();
        $this->createdAt = $event->getCreatedAt();
        $this->content = $event->getContent();
    }

    /**
     * @param CommentContentChangedEvent $event
     */
    protected function applyCommentContentChangedEvent(CommentContentChangedEvent $event): void
    {
        $this->content = $event->getTo();
        $this->editedAt = $event->getEditedAt();
    }
}
