<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Entity;

use Ergonode\Attribute\Domain\Event\Attribute\AttributeBoolParameterChangeEvent;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeCreatedEvent;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeHintChangedEvent;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeLabelChangedEvent;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeStringParameterChangeEvent;
use Ergonode\Attribute\Domain\Event\Attribute\AttributePlaceholderChangedEvent;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeScopeChangedEvent;
use Ergonode\Attribute\Domain\Event\AttributeGroupAddedEvent;
use Ergonode\Attribute\Domain\Event\AttributeGroupRemovedEvent;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

abstract class AbstractAttribute extends AbstractAggregateRoot implements AttributeInterface
{
    protected AttributeId $id;

    protected AttributeCode $code;

    protected TranslatableString $label;

    protected TranslatableString $hint;

    protected AttributeScope $scope;

    protected TranslatableString $placeholder;

    /**
     * @var AttributeGroupId[]
     */
    protected array $groups;

    /**
     * @var array
     */
    protected array $parameters;

    /**
     * @param array $parameters
     *
     * @throws \Exception
     */
    public function __construct(
        AttributeId $id,
        AttributeCode $code,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        AttributeScope $scope,
        array $parameters = []
    ) {
        $this->apply(
            new AttributeCreatedEvent(
                $id,
                $code,
                $label,
                $hint,
                $placeholder,
                $scope,
                $this->getType(),
                $parameters,
                $this->isSystem()
            )
        );
    }

    abstract public function getType(): string;

    public function getId(): AttributeId
    {
        return $this->id;
    }

    public function getCode(): AttributeCode
    {
        return $this->code;
    }

    public function getScope(): AttributeScope
    {
        return $this->scope;
    }

    public function isSystem(): bool
    {
        return false;
    }

    public function isEditable(): bool
    {
        return true;
    }

    public function isDeletable(): bool
    {
        return true;
    }

    public function isMultilingual(): bool
    {
        return true;
    }

    /**
     * @throws \Exception
     */
    public function changeLabel(TranslatableString $label): void
    {
        if (!$label->isEqual($this->label)) {
            $this->apply(new AttributeLabelChangedEvent($this->id, $label));
        }
    }

    /**
     * @throws \Exception
     */
    public function changeHint(TranslatableString $hint): void
    {
        if (!$hint->isEqual($this->hint)) {
            $this->apply(new AttributeHintChangedEvent($this->id, $hint));
        }
    }

    /**
     * @throws \Exception
     */
    public function changePlaceholder(TranslatableString $placeholder): void
    {
        if (!$placeholder->isEqual($this->placeholder)) {
            $this->apply(new AttributePlaceholderChangedEvent($this->id, $placeholder));
        }
    }

    /**
     * @throws \Exception
     */
    public function changeScope(AttributeScope $scope): void
    {
        if (!$scope->isEqual($this->scope)) {
            $this->apply(new AttributeScopeChangedEvent($this->id, $scope));
        }
    }

    public function getLabel(): TranslatableString
    {
        return $this->label;
    }

    public function getHint(): TranslatableString
    {
        return $this->hint;
    }

    public function getPlaceholder(): TranslatableString
    {
        return $this->placeholder;
    }

    /**
     * @return AttributeGroupId[]
     */
    public function getGroups(): array
    {
        return array_values($this->groups);
    }

    public function inGroup(AttributeGroupId $groupId): bool
    {
        return isset($this->groups[$groupId->getValue()]);
    }

    /**
     * @throws \Exception
     */
    public function addGroup(AttributeGroupId $groupId): void
    {
        if (!$this->inGroup($groupId)) {
            $this->apply(new AttributeGroupAddedEvent($this->id, $groupId));
        }
    }

    /**
     * @throws \Exception
     */
    public function removeGroup(AttributeGroupId $groupId): void
    {
        if ($this->inGroup($groupId)) {
            $this->apply(new AttributeGroupRemovedEvent($this->id, $groupId));
        }
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param mixed $value
     */
    protected function setParameter(string $name, $value): void
    {
        $this->parameters[$name] = $value;
    }

    /**
     * @return mixed
     */
    protected function getParameter(string $name)
    {
        return $this->parameters[$name];
    }

    protected function applyAttributeCreatedEvent(AttributeCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->code = $event->getCode();
        $this->label = $event->getLabel();
        $this->groups = [];
        $this->hint = $event->getHint();
        $this->scope = $event->getScope();
        $this->placeholder = $event->getPlaceholder();
        $this->parameters = $event->getParameters();
    }

    protected function applyAttributeGroupAddedEvent(AttributeGroupAddedEvent $event): void
    {
        $this->groups[$event->getGroupId()->getValue()] = $event->getGroupId();
    }

    protected function applyAttributeGroupRemovedEvent(AttributeGroupRemovedEvent $event): void
    {
        unset($this->groups[$event->getGroupId()->getValue()]);
    }

    protected function applyAttributeLabelChangedEvent(AttributeLabelChangedEvent $event): void
    {
        $this->label = $event->getTo();
    }

    protected function applyAttributeHintChangedEvent(AttributeHintChangedEvent $event): void
    {
        $this->hint = $event->getTo();
    }

    protected function applyAttributePlaceholderChangedEvent(AttributePlaceholderChangedEvent $event): void
    {
        $this->placeholder = $event->getTo();
    }

    protected function applyAttributeScopeChangedEvent(AttributeScopeChangedEvent $event): void
    {
        $this->scope = $event->getTo();
    }

    protected function applyAttributeStringParameterChangeEvent(AttributeStringParameterChangeEvent $event): void
    {
        $this->setParameter($event->getName(), $event->getTo());
    }

    protected function applyAttributeBoolParameterChangeEvent(AttributeBoolParameterChangeEvent $event): void
    {
        $this->setParameter($event->getName(), $event->getTo());
    }
}
