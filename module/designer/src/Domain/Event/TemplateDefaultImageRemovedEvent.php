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

/**
 */
class TemplateDefaultImageRemovedEvent implements DomainEventInterface
{
    /**
     * @var TemplateId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TemplateId")
     */
    private TemplateId $id;

    /**
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $defaultImage;

    /**
     * @param TemplateId  $id
     * @param AttributeId $defaultImage
     */
    public function __construct(TemplateId $id, AttributeId $defaultImage)
    {
        $this->id = $id;
        $this->defaultImage = $defaultImage;
    }

    /**
     * @return TemplateId
     */
    public function getAggregateId(): TemplateId
    {
        return $this->id;
    }

    /**
     * @return AttributeId
     */
    public function getDefaultImage(): AttributeId
    {
        return $this->defaultImage;
    }
}
