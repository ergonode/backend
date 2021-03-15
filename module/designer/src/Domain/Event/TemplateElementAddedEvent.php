<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;

class TemplateElementAddedEvent implements AggregateEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TemplateId")
     */
    private TemplateId $id;

    /**
     * @JMS\Type("Ergonode\Designer\Domain\Entity\TemplateElementInterface")
     */
    private TemplateElementInterface $element;

    public function __construct(TemplateId $id, TemplateElementInterface $element)
    {
        $this->id = $id;
        $this->element = $element;
    }

    public function getAggregateId(): TemplateId
    {
        return $this->id;
    }

    public function getElement(): TemplateElementInterface
    {
        return $this->element;
    }
}
