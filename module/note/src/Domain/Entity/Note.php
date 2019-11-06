<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Note\Domain\Entity;

use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Note\Domain\Event\NoteContentChangedEvent;
use Ergonode\Note\Domain\Event\NoteCreatedEvent;
use JMS\Serializer\Annotation as JMS;
use Ramsey\Uuid\Uuid;

/**
 */
class Note extends AbstractAggregateRoot
{
    /**
     * @var NoteId $id
     *
     * @JMS\Type("Ergonode\Note\Domain\Entity\NoteId")
     */
    private $id;

    /**
     * @var UserId $authorId
     *
     * @JMS\Type("Ergonode\Account\Domain\Entity\UserId")
     */
    private $authorId;

    /**
     * @var Uuid
     *
     * @JMS\Type("uuid")
     */
    private $objectId;

    /**
     * @var \DateTime $createdAt
     *
     * @JMS\Type("DateTime")
     */
    private $createdAt;

    /**
     * @var \DateTime $editedAt
     *
     * @JMS\Type("DateTime")
     */
    private $editedAt;

    /**
     * @var string $content
     *
     * @JMS\Type("string")
     */
    private $content;

    /**
     * @param NoteId $id
     * @param Uuid   $objectId
     * @param UserId $authorId
     * @param string $content
     *
     * @throws \Exception
     */
    public function __construct(NoteId $id, Uuid $objectId, UserId $authorId, string $content)
    {
        $this->apply(new NoteCreatedEvent($id, $authorId, $objectId, $content, new \DateTime()));
    }

    /**
     * @param string $contend
     */
    public function changeContent(string $contend): void
    {
        if ($contend !== $this->content) {
            $this->apply(new NoteContentChangedEvent($this->content, $contend, new \DateTime()));
        }
    }

    /**
     * @return NoteId
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
     * @param NoteCreatedEvent $event
     */
    protected function applyNoteCreatedEvent(NoteCreatedEvent $event): void
    {
        $this->id = $event->getId();
        $this->authorId = $event->getAuthorId();
        $this->objectId = $event->getObjectId();
        $this->createdAt = $event->getCreatedAt();
        $this->content = $event->getContent();
    }

    /**
     * @param NoteContentChangedEvent $event
     */
    protected function applyNoteContentChangedEvent(NoteContentChangedEvent $event): void
    {
        $this->content = $event->getTo();
        $this->editedAt = $event->getEditedAt();
    }
}
