<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Domain\Event;

use Ergonode\Comment\Domain\Entity\CommentId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CommentContentChangedEvent implements DomainEventInterface
{
    /**
     * @var CommentId $id
     *
     * @JMS\Type("Ergonode\Comment\Domain\Entity\CommentId")
     */
    private $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $from;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $to;

    /**
     * @var \DateTime
     *
     * @JMS\Type("DateTime")
     */
    private $editedAt;

    /**
     * @param CommentId $id
     * @param string    $from
     * @param string    $to
     * @param \DateTime $editedAt
     */
    public function __construct(CommentId $id, string $from, string $to, \DateTime $editedAt)
    {
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
        $this->editedAt = $editedAt;
    }

    /**
     * @return CommentId|AbstractId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @return \DateTime
     */
    public function getEditedAt(): \DateTime
    {
        return $this->editedAt;
    }
}
