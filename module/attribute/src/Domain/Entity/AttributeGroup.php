<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Entity;

use Ergonode\Attribute\Domain\Event\Group\AttributeGroupCreatedEvent;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Core\Domain\Entity\AbstractId;

/**
 */
class AttributeGroup extends AbstractAggregateRoot
{
    /**
     * @var AttributeGroupId
     */
    private $id;

    /**
     * @var string
     */
    private $label;

    /**
     * @param AttributeGroupId $id
     * @param string           $label
     */
    public function __construct(AttributeGroupId $id, string $label)
    {
        $this->apply(new AttributeGroupCreatedEvent($id, $label));
    }

    /**
     * @return AttributeGroupId
     */
    public function getId(): AbstractId
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

    /**
     * @param AttributeGroupCreatedEvent $event
     */
    protected function app(AttributeGroupCreatedEvent $event): void
    {
        $this->id = $event->getId();
        $this->label = $event->getLabel();
    }
}
