<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

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
use JMS\Serializer\Annotation as JMS;

/**
 */
abstract class AbstractAttribute extends AbstractAggregateRoot implements AttributeInterface
{
    /**
     * @var AttributeId
     */
    protected AttributeId $id;

    /**
     * @var AttributeCode
     */
    protected AttributeCode $code;

    /**
     * @var TranslatableString
     */
    protected TranslatableString $label;

    /**
     * @var TranslatableString
     */
    protected TranslatableString $hint;

    /**
     * @var AttributeScope $scope
     */
    protected AttributeScope $scope;

    /**
     * @var TranslatableString
     */
    protected TranslatableString $placeholder;

    /**
     * @var array
     */
    protected array $groups;

    /**
     * @var array
     */
    protected array $parameters;

    /**
     * @param AttributeId        $id
     * @param AttributeCode      $code
     * @param TranslatableString $label
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     * @param AttributeScope     $scope
     * @param array              $parameters
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
                \get_class($this),
                $parameters,
                $this->isSystem()
            )
        );
    }

    /**
     * @JMS\VirtualProperty();
     * @JMS\SerializedName("type")
     *
     * @return string
     */
    abstract public function getType(): string;

    /**
     * @return AttributeId
     */
    public function getId(): AttributeId
    {
        return $this->id;
    }

    /**
     * @return AttributeCode
     */
    public function getCode(): AttributeCode
    {
        return $this->code;
    }

    /**
     * @return AttributeScope
     */
    public function getScope(): AttributeScope
    {
        return $this->scope;
    }

    /**
     * @return bool
     */
    public function isSystem(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isEditable(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isDeletable(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isMultilingual(): bool
    {
        return true;
    }

    /**
     * @param TranslatableString $label
     *
     * @throws \Exception
     */
    public function changeLabel(TranslatableString $label): void
    {
        if (!$label->isEqual($this->label)) {
            $this->apply(new AttributeLabelChangedEvent($this->id, $this->label, $label));
        }
    }

    /**
     * @param TranslatableString $hint
     *
     * @throws \Exception
     */
    public function changeHint(TranslatableString $hint): void
    {
        if (!$hint->isEqual($this->hint)) {
            $this->apply(new AttributeHintChangedEvent($this->id, $this->hint, $hint));
        }
    }

    /**
     * @param TranslatableString $placeholder
     *
     * @throws \Exception
     */
    public function changePlaceholder(TranslatableString $placeholder): void
    {
        if (!$placeholder->isEqual($this->placeholder)) {
            $this->apply(new AttributePlaceholderChangedEvent($this->id, $this->placeholder, $placeholder));
        }
    }

    /**
     * @param AttributeScope $scope
     *
     * @throws \Exception
     */
    public function changeScope(AttributeScope $scope): void
    {
        if (!$scope->isEqual($this->scope)) {
            $this->apply(new AttributeScopeChangedEvent($this->id, $this->scope, $scope));
        }
    }

    /**
     * @return TranslatableString
     */
    public function getLabel(): TranslatableString
    {
        return $this->label;
    }

    /**
     * @return TranslatableString
     */
    public function getHint(): TranslatableString
    {
        return $this->hint;
    }

    /**
     * @return TranslatableString
     */
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

    /**
     * @param AttributeGroupId $groupId
     *
     * @return bool
     */
    public function inGroup(AttributeGroupId $groupId): bool
    {
        return isset($this->groups[$groupId->getValue()]);
    }

    /**
     * @param AttributeGroupId $groupId
     *
     * @throws \Exception
     */
    public function addGroup(AttributeGroupId $groupId): void
    {
        if (!$this->inGroup($groupId)) {
            $this->apply(new AttributeGroupAddedEvent($this->id, $groupId));
        }
    }

    /**
     * @param AttributeGroupId $groupId
     *
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
     * @param string $name
     * @param mixed  $value
     */
    protected function setParameter(string $name, $value): void
    {
        $this->parameters[$name] = $value;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    protected function getParameter(string $name)
    {
        return $this->parameters[$name];
    }

    /**
     * @param AttributeCreatedEvent $event
     */
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

    /**
     * @param AttributeGroupAddedEvent $event
     */
    protected function applyAttributeGroupAddedEvent(AttributeGroupAddedEvent $event): void
    {
        $this->groups[$event->getGroupId()->getValue()] = $event->getGroupId();
    }

    /**
     * @param AttributeGroupRemovedEvent $event
     */
    protected function applyAttributeGroupRemovedEvent(AttributeGroupRemovedEvent $event): void
    {
        unset($this->groups[$event->getGroupId()->getValue()]);
    }

    /**
     * @param AttributeLabelChangedEvent $event
     */
    protected function applyAttributeLabelChangedEvent(AttributeLabelChangedEvent $event): void
    {
        $this->label = $event->getTo();
    }

    /**
     * @param AttributeHintChangedEvent $event
     */
    protected function applyAttributeHintChangedEvent(AttributeHintChangedEvent $event): void
    {
        $this->hint = $event->getTo();
    }

    /**
     * @param AttributePlaceholderChangedEvent $event
     */
    protected function applyAttributePlaceholderChangedEvent(AttributePlaceholderChangedEvent $event): void
    {
        $this->placeholder = $event->getTo();
    }

    /**
     * @param AttributeScopeChangedEvent $event
     */
    protected function applyAttributeScopeChangedEvent(AttributeScopeChangedEvent $event): void
    {
        $this->scope = $event->getTo();
    }

    /**
     * @param AttributeStringParameterChangeEvent $event
     */
    protected function applyAttributeStringParameterChangeEvent(AttributeStringParameterChangeEvent $event): void
    {
        $this->setParameter($event->getName(), $event->getTo());
    }

    /**
     * @param AttributeBoolParameterChangeEvent $event
     */
    protected function applyAttributeBoolParameterChangeEvent(AttributeBoolParameterChangeEvent $event): void
    {
        $this->setParameter($event->getName(), $event->getTo());
    }
}
