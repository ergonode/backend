<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Event\Group;

use Ergonode\Attribute\Domain\Entity\AttributeGroupId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use Ergonode\EventSourcing\Infrastructure\DomainAggregateEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class AttributeGroupDeletedEvent extends AbstractDeleteEvent implements DomainAggregateEventInterface
{
    /**
     * @var AttributeGroupId
     *
     * @JMS\Type("Ergonode\Attribute\Domain\Entity\AttributeGroupId")
     */
    private $id;

    /**
     * @param AttributeGroupId $id
     */
    public function __construct(AttributeGroupId $id)
    {
        $this->id = $id;
    }

    /**
     * @return AttributeGroupId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }
}
