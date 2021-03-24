<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Entity;

use Ergonode\Attribute\Domain\Event\Group\AttributeGroupCreatedEvent;
use Ergonode\Attribute\Domain\Event\Group\AttributeGroupNameChangedEvent;
use Ergonode\Attribute\Domain\ValueObject\AttributeGroupCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;

class AttributeGroup extends AbstractAggregateRoot
{
    private AttributeGroupId $id;

    private AttributeGroupCode $code;

    private TranslatableString $name;

    /**
     * @throws \Exception
     */
    public function __construct(AttributeGroupId $id, AttributeGroupCode $code, TranslatableString $name)
    {
        $this->apply(new AttributeGroupCreatedEvent($id, $code, $name));
    }

    /**
     * @throws \Exception
     */
    public function changeName(TranslatableString $name): void
    {
        if (!$name->isEqual($this->name)) {
            $this->apply(new AttributeGroupNameChangedEvent($this->id, $name));
        }
    }

    public function getId(): AttributeGroupId
    {
        return $this->id;
    }

    public function getCode(): AttributeGroupCode
    {
        return $this->code;
    }

    public function getName(): TranslatableString
    {
        return $this->name;
    }

    protected function applyAttributeGroupCreatedEvent(AttributeGroupCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->code = $event->getCode();
        $this->name = $event->getName();
    }

    protected function applyAttributeGroupNameChangedEvent(AttributeGroupNameChangedEvent $event): void
    {
        $this->name = $event->getTo();
    }
}
