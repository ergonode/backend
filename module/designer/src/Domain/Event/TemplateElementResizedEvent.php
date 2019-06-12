<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Designer\Domain\Entity\TemplateElementId;
use Ergonode\Designer\Domain\ValueObject\Size;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TemplateElementResizedEvent implements DomainEventInterface
{
    /**
     * @var TemplateElementId
     *
     * @JMS\Type("Ergonode\Designer\Domain\Entity\TemplateElementId")
     */
    private $elementId;

    /**
     * @var Size
     *
     * @JMS\Type("Ergonode\Designer\Domain\ValueObject\Size")
     */
    private $from;

    /**
     * @var Size
     *
     * @JMS\Type("Ergonode\Designer\Domain\ValueObject\Size")
     */
    private $to;

    /**
     * @param TemplateElementId $elementId
     * @param Size              $from
     * @param Size              $to
     */
    public function __construct(TemplateElementId $elementId, Size $from, Size $to)
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
     * @return Size
     */
    public function getFrom(): Size
    {
        return $this->from;
    }

    /**
     * @return Size
     */
    public function getTo(): Size
    {
        return $this->to;
    }
}
