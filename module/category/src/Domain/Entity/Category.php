<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Domain\Entity;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Category\Domain\Event\CategoryCreatedEvent;
use Ergonode\Category\Domain\Event\CategoryNameChangedEvent;
use Ergonode\Category\Domain\ValueObject\CategoryCode;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Value\Domain\Event\ValueAddedEvent;
use Ergonode\Value\Domain\Event\ValueChangedEvent;
use Ergonode\Value\Domain\Event\ValueRemovedEvent;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class Category extends AbstractAggregateRoot
{
    /**
     * @var CategoryId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CategoryId")
     */
    private CategoryId $id;

    /**
     * @var CategoryCode
     *
     * @JMS\Type("Ergonode\Category\Domain\ValueObject\CategoryCode")
     */
    private CategoryCode $code;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $name;

    /**
     * @var ValueInterface[]
     *
     * @JMS\Type("array<string, Ergonode\Value\Domain\ValueObject\ValueInterface>")
     */
    private array $attributes;

    /**
     * @param CategoryId         $id
     * @param CategoryCode       $code
     * @param TranslatableString $name
     * @param array              $attributes
     *
     * @throws \Exception
     */
    public function __construct(CategoryId $id, CategoryCode $code, TranslatableString $name, array $attributes = [])
    {
        Assert::allIsInstanceOf($attributes, ValueInterface::class);

        $this->apply(new CategoryCreatedEvent($id, $code, $name, $attributes));
    }

    /**
     * @return CategoryId
     */
    public function getId(): CategoryId
    {
        return $this->id;
    }

    /**
     * @return CategoryCode
     */
    public function getCode(): CategoryCode
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
     * @param TranslatableString $title
     *
     * @throws \Exception
     */
    public function changeName(TranslatableString $title): void
    {
        if ($this->name->getTranslations() !== $title->getTranslations()) {
            $this->apply(new CategoryNameChangedEvent($this->id, $this->name, $title));
        }
    }

    /**
     * @param AttributeCode $attributeCode
     *
     * @return bool
     */
    public function hasAttribute(AttributeCode $attributeCode): bool
    {
        return isset($this->attributes[$attributeCode->getValue()]);
    }

    /**
     * @param AttributeCode $attributeCode
     *
     * @return ValueInterface
     */
    public function getAttribute(AttributeCode $attributeCode): ValueInterface
    {
        if (!$this->hasAttribute($attributeCode)) {
            throw new \RuntimeException(sprintf('Value for attribute %s not exists', $attributeCode->getValue()));
        }

        return clone $this->attributes[$attributeCode->getValue()];
    }

    /**
     * @param AttributeCode  $attributeCode
     * @param ValueInterface $value
     *
     * @throws \Exception
     */
    public function addAttribute(AttributeCode $attributeCode, ValueInterface $value): void
    {
        if ($this->hasAttribute($attributeCode)) {
            throw new \RuntimeException('Value already exists');
        }

        $this->apply(new ValueAddedEvent($this->id, $attributeCode, $value));
    }

    /**
     * @return ValueInterface[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param AttributeCode  $attributeCode
     * @param ValueInterface $value
     *
     * @throws \Exception
     */
    public function changeAttribute(AttributeCode $attributeCode, ValueInterface $value): void
    {
        if (!$this->hasAttribute($attributeCode)) {
            throw new \RuntimeException('Value note exists');
        }

        if ((string) $this->attributes[$attributeCode->getValue()] !== (string) $value) {
            $this->apply(
                new ValueChangedEvent(
                    $this->id,
                    $attributeCode,
                    $this->attributes[$attributeCode->getValue()],
                    $value
                )
            );
        }
    }

    /**
     * @param AttributeCode $attributeCode
     *
     * @throws \Exception
     */
    public function removeAttribute(AttributeCode $attributeCode): void
    {
        if (!$this->hasAttribute($attributeCode)) {
            throw new \RuntimeException('Value note exists');
        }

        $this->apply(new ValueRemovedEvent($this->id, $attributeCode, $this->attributes[$attributeCode->getValue()]));
    }

    /**
     * @param CategoryCreatedEvent $event
     */
    protected function applyCategoryCreatedEvent(CategoryCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->code = $event->getCode();
        $this->name = $event->getName();
        $this->attributes = $event->getAttributes();
    }

    /**
     * @param CategoryNameChangedEvent $event
     */
    protected function applyCategoryNameChangedEvent(CategoryNameChangedEvent $event): void
    {
        $this->name = $event->getTo();
    }

    /**
     * @param ValueAddedEvent $event
     */
    protected function applyValueAddedEvent(ValueAddedEvent $event): void
    {
        $this->attributes[$event->getAttributeCode()->getValue()] = $event->getValue();
    }

    /**
     * @param ValueChangedEvent $event
     */
    protected function applyValueChangedEvent(ValueChangedEvent $event): void
    {
        $this->attributes[$event->getAttributeCode()->getValue()] = $event->getTo();
    }

    /**
     * @param ValueRemovedEvent $event
     */
    protected function applyValueRemovedEvent(ValueRemovedEvent $event): void
    {
        unset($this->attributes[$event->getAttributeCode()->getValue()]);
    }
}
