<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeArrayParameterChangeEvent;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeCreatedEvent;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeParameterChangeEvent;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeHintChangedEvent;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeLabelChangedEvent;
use Ergonode\Attribute\Domain\Event\Attribute\AttributePlaceholderChangedEvent;
use Ergonode\Attribute\Domain\Event\AttributeGroupAddedEvent;
use Ergonode\Attribute\Domain\Event\AttributeGroupRemovedEvent;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use JMS\Serializer\Annotation as JMS;

/**
 */
abstract class AbstractAttribute extends AbstractAggregateRoot
{
    /**
     * @var AttributeId
     */
    protected $id;

    /**
     * @var AttributeCode
     */
    protected $code;

    /**
     * @var TranslatableString
     */
    protected $label;

    /**
     * @var TranslatableString
     */
    protected $hint;

    /**
     * @var bool
     */
    protected $multilingual;

    /**
     * @var TranslatableString
     */
    protected $placeholder;

    /**
     * @var array
     */
    protected $groups;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @param AttributeId        $id
     * @param AttributeCode      $code
     * @param TranslatableString $label
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     * @param bool               $multilingual
     * @param array              $parameters
     */
    public function __construct(
        AttributeId $id,
        AttributeCode $code,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        bool $multilingual,
        array $parameters = []
    ) {
        $this->apply(
            new AttributeCreatedEvent(
                $id,
                $code,
                $label,
                $hint,
                $placeholder,
                $multilingual,
                $this->getType(),
                \get_class($this),
                $parameters
            )
        );
    }

    /**
     * @JMS\VirtualProperty();
     * @JMS\SerializedName("type")
     *
     * @return string
     *
     * @todo chane to AttributeType (static) ?
     */
    abstract public function getType(): string;

    /**
     * @return AttributeId|AbstractId
     */
    public function getId(): AbstractId
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
     * @return bool
     */
    public function isMultilingual(): bool
    {
        return $this->multilingual;
    }

    /**
     * @param TranslatableString $label
     */
    public function changeLabel(TranslatableString $label): void
    {
        if ($this->label->getTranslations() !== $label->getTranslations()) {
            $this->apply(new AttributeLabelChangedEvent($this->label, $label));
        }
    }

    /**
     * @param TranslatableString $hint
     */
    public function changeHint(TranslatableString $hint): void
    {
        if ($this->hint->getTranslations() !== $hint->getTranslations()) {
            $this->apply(new AttributeHintChangedEvent($this->hint, $hint));
        }
    }

    /**
     * @param TranslatableString $placeholder
     */
    public function changePlaceholder(TranslatableString $placeholder): void
    {
        if ($this->placeholder->getTranslations() !== $placeholder->getTranslations()) {
            $this->apply(new AttributePlaceholderChangedEvent($this->placeholder, $placeholder));
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
     * @return ArrayCollection|AttributeGroupId[]
     */
    public function getGroups(): ArrayCollection
    {
        return new ArrayCollection($this->groups);
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
     */
    public function addGroup(AttributeGroupId $groupId): void
    {
        if (!$this->inGroup($groupId)) {
            $this->apply(new AttributeGroupAddedEvent($groupId));
        }
    }

    /**
     * @param AttributeGroupId $groupId
     */
    public function removeGroup(AttributeGroupId $groupId): void
    {
        if ($this->inGroup($groupId)) {
            $this->apply(new AttributeGroupRemovedEvent($groupId));
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
     * @param string $name
     *
     * @return bool
     */
    protected function hasParameter(string $name): bool
    {
        return isset($this->parameters[$name]);
    }

    /**
     * @param AttributeCreatedEvent $event
     */
    protected function applyAttributeCreatedEvent(AttributeCreatedEvent $event): void
    {
        $this->id = $event->getId();
        $this->code = $event->getCode();
        $this->label = $event->getLabel();
        $this->groups = [];
        $this->hint = $event->getHint();
        $this->multilingual = $event->isMultilingual();
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
     * @param AttributeParameterChangeEvent $event
     */
    protected function applyAttributeParameterChangeEvent(AttributeParameterChangeEvent $event): void
    {
        $this->setParameter($event->getName(), $event->getTo());
    }

    /**
     * @param AttributeArrayParameterChangeEvent $event
     */
    protected function applyAttributeArrayParameterChangeEvent(AttributeArrayParameterChangeEvent $event): void
    {
        $this->setParameter($event->getName(), $event->getTo());
    }
}
