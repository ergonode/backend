<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Event\Attribute;

use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use JMS\Serializer\Annotation as JMS;

/**
 */
class AttributeDeletedEvent extends AbstractDeleteEvent
{
    /**
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\Attribute\Domain\Entity\AttributeId")
     */
    private $id;

    /**
     * @param AttributeId $id
     */
    public function __construct(AttributeId $id)
    {
        $this->id = $id;
    }

    /**
     * @return AttributeId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }
}
