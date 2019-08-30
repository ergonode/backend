<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Event\Group;

use Ergonode\Attribute\Domain\Entity\AttributeGroupId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class AttributeGroupCreatedEvent implements DomainEventInterface
{
    /**
     * @var AttributeGroupId
     *
     * @JMS\Type("Ergonode\Attribute\Domain\Entity\AttributeGroupId")
     */
    private $id;

      /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $label;

    /**
     * @param AttributeGroupId $id
     * @param string           $label
     */
    public function __construct(AttributeGroupId $id, string $label)
    {
        $this->id = $id;
        $this->label = $label;
    }

    /**
     * @return AttributeGroupId
     */
    public function getId(): AttributeGroupId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }
}
