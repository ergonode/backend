<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Event;

use Ergonode\Attribute\Domain\Entity\AttributeGroupId;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainAggregateEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class AttributeGroupRemovedEvent implements DomainAggregateEventInterface
{
    /**
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\Attribute\Domain\Entity\AttributeId")
     */
    private $id;

    /**
     * @var AttributeGroupId
     *
     * @JMS\Type("Ergonode\Attribute\Domain\Entity\AttributeGroupId")
     */
    private $groupId;

    /**
     * @param AttributeId      $id
     * @param AttributeGroupId $groupId
     */
    public function __construct(AttributeId $id, AttributeGroupId $groupId)
    {
        $this->id = $id;
        $this->groupId = $groupId;
    }

    /**
     * @return AttributeId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return AttributeGroupId
     */
    public function getGroupId(): AttributeGroupId
    {
        return $this->groupId;
    }
}
