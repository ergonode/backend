<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\Designer\Domain\Entity\TemplateElement;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use JMS\Serializer\Annotation as JMS;

class TemplateElementAddedEvent implements DomainEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TemplateId")
     */
    private TemplateId $id;

    /**
     * @JMS\Type("Ergonode\Designer\Domain\Entity\TemplateElement")
     */
    private TemplateElement $element;

    public function __construct(TemplateId $id, TemplateElement $element)
    {
        $this->id = $id;
        $this->element = $element;
    }

    public function getAggregateId(): TemplateId
    {
        return $this->id;
    }

    public function getElement(): TemplateElement
    {
        return $this->element;
    }
}
