<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Event;

use Ergonode\Attribute\Domain\Entity\AttributeGroupId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class AttributeGroupRemovedEvent implements DomainEventInterface
{
    /**
     * @var AttributeGroupId
     *
     * @JMS\Type("Ergonode\Attribute\Domain\Entity\AttributeGroupId")
     */
    private $groupId;

    /**
     * @param AttributeGroupId $groupId
     */
    public function __construct(AttributeGroupId $groupId)
    {
        $this->groupId = $groupId;
    }

    /**
     * @return AttributeGroupId
     */
    public function getGroupId(): AttributeGroupId
    {
        return $this->groupId;
    }
}
