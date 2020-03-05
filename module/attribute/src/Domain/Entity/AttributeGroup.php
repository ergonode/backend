<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Entity;

use Ergonode\Attribute\Domain\Event\Group\AttributeGroupCreatedEvent;
use Ergonode\Attribute\Domain\Event\Group\AttributeGroupNameChangedEvent;
use Ergonode\Attribute\Domain\ValueObject\AttributeGroupCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;

/**
 */
class AttributeGroup extends AbstractAggregateRoot
{
    /**
     * @var AttributeGroupId
     */
    private AttributeGroupId $id;

    /**
     * @var AttributeGroupCode
     */
    private AttributeGroupCode $code;

    /**
     * @var TranslatableString
     */
    private TranslatableString $name;

    /**
     * @param AttributeGroupId   $id
     * @param AttributeGroupCode $code
     * @param TranslatableString $name
     *
     * @throws \Exception
     */
    public function __construct(AttributeGroupId $id, AttributeGroupCode $code, TranslatableString $name)
    {
        $this->apply(new AttributeGroupCreatedEvent($id, $code, $name));
    }

    /**
     * @param TranslatableString $name
     *
     * @throws \Exception
     */
    public function changeName(TranslatableString $name): void
    {
        if (!$name->isEqual($this->name)) {
            $this->apply(new AttributeGroupNameChangedEvent($this->id, $this->name, $name));
        }
    }

    /**
     * @return AttributeGroupId
     */
    public function getId(): AttributeGroupId
    {
        return $this->id;
    }

    /**
     * @return AttributeGroupCode
     */
    public function getCode(): AttributeGroupCode
    {
        return $this->code;
    }

    /**
     * @return TranslatableString
     */
    public function getName(): TranslatableString
    {
        return $this->name;
    }

    /**
     * @param AttributeGroupCreatedEvent $event
     */
    protected function applyAttributeGroupCreatedEvent(AttributeGroupCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->code = $event->getCode();
        $this->name = $event->getName();
    }

    /**
     * @param AttributeGroupNameChangedEvent $event
     */
    protected function applyAttributeGroupNameChangedEvent(AttributeGroupNameChangedEvent $event): void
    {
        $this->name = $event->getTo();
    }
}
