<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use JMS\Serializer\Annotation as JMS;

class TemplateDefaultLabelAddedEvent implements DomainEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TemplateId")
     */
    private TemplateId $id;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $defaultLabel;

    public function __construct(TemplateId $id, AttributeId $defaultLabel)
    {
        $this->id = $id;
        $this->defaultLabel = $defaultLabel;
    }

    public function getAggregateId(): TemplateId
    {
        return $this->id;
    }

    public function getDefaultLabel(): AttributeId
    {
        return $this->defaultLabel;
    }
}
