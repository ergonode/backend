<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Domain\Event;

use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Comment\Domain\Entity\CommentId;
use JMS\Serializer\Annotation as JMS;
use Ramsey\Uuid\Uuid;

/**
 */
class CommentCreatedEvent implements DomainEventInterface
{
    /**
     * @var CommentId $id
     *
     * @JMS\Type("Ergonode\Comment\Domain\Entity\CommentId")
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
     * @var string $content
     *
     * @JMS\Type("string")
     */
    private $content;

    /**
     * @var \DateTime $createdAt
     *
     * @JMS\Type("DateTime")
     */
    private $createdAt;

    /**
     * @param CommentId $id
     * @param UserId    $authorId
     * @param Uuid      $objectId
     * @param string    $content
     * @param \DateTime $createdAt
     */
    public function __construct(CommentId $id, UserId $authorId, Uuid $objectId, string $content, \DateTime $createdAt)
    {
        $this->id = $id;
        $this->authorId = $authorId;
        $this->objectId = $objectId;
        $this->content = $content;
        $this->createdAt = $createdAt;
    }

    /**
     * @return CommentId
     */
    public function getId(): CommentId
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
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}
