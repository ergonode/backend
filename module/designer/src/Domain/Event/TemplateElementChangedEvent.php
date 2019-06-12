<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\Designer\Domain\Entity\AbstractTemplateElement;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TemplateElementChangedEvent implements DomainEventInterface
{
    /**
     * @var AbstractTemplateElement
     *
     * @JMS\Type("Ergonode\Designer\Domain\Entity\AbstractTemplateElement")
     */
    private $element;

    /**
     * @param AbstractTemplateElement $element
     */
    public function __construct(AbstractTemplateElement $element)
    {
        $this->element = $element;
    }

    /**
     * @return AbstractTemplateElement
     */
    public function getElement(): AbstractTemplateElement
    {
        return $this->element;
    }
}
