<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Designer\Domain\Entity\TemplateElementId;
use Ergonode\Designer\Domain\ValueObject\Position;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TemplateElementMovedEvent implements DomainEventInterface
{
    /**
     * @var TemplateElementId
     *
     * @JMS\Type("Ergonode\Designer\Domain\Entity\TemplateElementId")
     */
    private $elementId;

    /**
     * @var Position
     *
     * @JMS\Type("Ergonode\Designer\Domain\ValueObject\Position")
     */
    private $from;

    /**
     * @var Position
     *
     * @JMS\Type("Ergonode\Designer\Domain\ValueObject\Position")
     */
    private $to;

    /**
     * @param TemplateElementId $elementId
     * @param Position          $from
     * @param Position          $to
     */
    public function __construct(TemplateElementId $elementId, Position $from, Position $to)
    {
        $this->elementId = $elementId;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return TemplateElementId
     */
    public function getElementId(): TemplateElementId
    {
        return $this->elementId;
    }

    /**
     * @return Position
     */
    public function getFrom(): Position
    {
        return $this->from;
    }

    /**
     * @return Position
     */
    public function getTo(): Position
    {
        return $this->to;
    }
}
