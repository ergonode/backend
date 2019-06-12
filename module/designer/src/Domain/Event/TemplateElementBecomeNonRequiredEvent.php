<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Designer\Domain\Entity\TemplateElementId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TemplateElementBecomeNonRequiredEvent implements DomainEventInterface
{
    /**
     * @var TemplateElementId
     *
     * @JMS\Type("Ergonode\Designer\Domain\Entity\TemplateElementId")
     */
    private $elementId;

    /**
     * @param TemplateElementId $elementId
     */
    public function __construct(TemplateElementId $elementId)
    {
        $this->elementId = $elementId;
    }

    /**
     * @return TemplateElementId
     */
    public function getElementId(): TemplateElementId
    {
        return $this->elementId;
    }
}
