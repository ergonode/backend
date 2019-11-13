<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CommentContentChangedEvent implements DomainEventInterface
{
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
     * @param string    $from
     * @param string    $to
     * @param \DateTime $editedAt
     */
    public function __construct(string $from, string $to, \DateTime $editedAt)
    {
        $this->from = $from;
        $this->to = $to;
        $this->editedAt = $editedAt;
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
