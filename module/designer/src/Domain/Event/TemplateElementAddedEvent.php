<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\Designer\Domain\Entity\TemplateElement;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TemplateElementAddedEvent implements DomainEventInterface
{
    /**
     * @var TemplateElement
     *
     * @JMS\Type("Ergonode\Designer\Domain\Entity\TemplateElement")
     */
    private $element;

    /**
     * @param TemplateElement $element
     */
    public function __construct(TemplateElement $element)
    {
        $this->element = $element;
    }

    /**
     * @return TemplateElement
     */
    public function getElement(): TemplateElement
    {
        return $this->element;
    }
}
