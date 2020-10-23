<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use JMS\Serializer\Annotation as JMS;

class TemplateDefaultImageRemovedEvent implements DomainEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TemplateId")
     */
    private TemplateId $id;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $defaultImage;

    public function __construct(TemplateId $id, AttributeId $defaultImage)
    {
        $this->id = $id;
        $this->defaultImage = $defaultImage;
    }

    public function getAggregateId(): TemplateId
    {
        return $this->id;
    }

    public function getDefaultImage(): AttributeId
    {
        return $this->defaultImage;
    }
}
