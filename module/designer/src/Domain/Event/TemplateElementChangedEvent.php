<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Designer\Domain\Entity\TemplateElement;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\EventSourcing\Infrastructure\DomainAggregateEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TemplateElementChangedEvent implements DomainAggregateEventInterface
{
    /**
     * @var TemplateId
     *
     * @JMS\Type("Ergonode\Designer\Domain\Entity\TemplateId")
     */
    private $id;

    /**
     * @var TemplateElement
     *
     * @JMS\Type("Ergonode\Designer\Domain\Entity\TemplateElement")
     */
    private $element;

    /**
     * @param TemplateId      $id
     * @param TemplateElement $element
     */
    public function __construct(TemplateId $id, TemplateElement $element)
    {
        $this->id = $id;
        $this->element = $element;
    }

    /**
     * @return AbstractId|TemplateId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return TemplateElement
     */
    public function getElement(): TemplateElement
    {
        return $this->element;
    }
}
