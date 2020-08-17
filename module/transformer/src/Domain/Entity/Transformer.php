<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;
use Ergonode\Transformer\Domain\Event\TransformerCreatedEvent;
use Ergonode\Transformer\Infrastructure\Converter\ConverterInterface;
use Ergonode\Transformer\Domain\Event\TransformerFieldAddedEvent;
use Ergonode\Transformer\Domain\Event\TransformerAttributeAddedEvent;
use JMS\Serializer\Annotation as JMS;

/**
 */
class Transformer extends AbstractAggregateRoot
{
    /**
     * @var TransformerId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TransformerId")
     */
    private TransformerId $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $key;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $name;

    /**
     * @var ConverterInterface[]
     *
     * @JMS\Type("array<Ergonode\Transformer\Infrastructure\Converter\ConverterInterface>")
     */
    private array $fields;

    /**
     * @var ConverterInterface[]
     *
     * @JMS\Type("array<string, Ergonode\Transformer\Infrastructure\Converter\ConverterInterface>")
     */
    private array $attributes;

    /**
     * @var string[]
     *
     * @JMS\Type("array<string, string>")
     */
    private array $attributeType;

    /**
     * @var bool[]
     *
     * @JMS\Type("array<string, boolean>")
     */
    private array $multilingual;

    /**
     * @param TransformerId $id
     * @param string        $name
     * @param string        $key
     *
     * @throws \Exception
     */
    public function __construct(TransformerId $id, string $name, string $key)
    {
        $this->apply(new TransformerCreatedEvent($id, $name, $key));
    }

    /**
     * @return TransformerId
     */
    public function getId(): TransformerId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string             $field
     * @param ConverterInterface $converter
     *
     * @return $this
     *
     * @throws \Exception
     */
    public function addField(string $field, ConverterInterface $converter): self
    {
        if ($this->hasField($field)) {
            throw new \InvalidArgumentException(sprintf('converter for field %s already exists', $field));
        }

        $this->apply(new TransformerFieldAddedEvent($this->id, $field, $converter));

        return $this;
    }

    /**
     * @param string             $field
     * @param string             $type
     * @param bool               $multilingual
     * @param ConverterInterface $converter
     *
     * @return $this
     *
     * @throws \Exception
     */
    public function addAttribute(string $field, string $type, bool $multilingual, ConverterInterface $converter): self
    {
        if ($this->hasAttribute($field)) {
            throw new \InvalidArgumentException(sprintf('converter for field %s already exists', $field));
        }

        $this->apply(new TransformerAttributeAddedEvent($this->id, $field, $converter, $type, $multilingual));

        return $this;
    }

    /**
     * @param string $field
     *
     * @return bool
     */
    public function hasField(string $field): bool
    {
        return isset($this->fields[$field]);
    }

    /**
     * @param string $field
     *
     * @return bool
     */
    public function hasAttribute(string $field): bool
    {
        return isset($this->attributes[$field]);
    }

    /**
     * @param string $field
     *
     * @return bool
     */
    public function isAttributeMultilingual(string $field): bool
    {
        if (!$this->hasAttribute($field)) {
            throw new \InvalidArgumentException(sprintf('attribute multilingual %s not exists', $field));
        }

        return $this->multilingual[$field];
    }

    /**
     * @param string $field
     *
     * @return string
     */
    public function getAttributeType(string $field): string
    {
        if (!$this->hasAttribute($field)) {
            throw new \InvalidArgumentException(sprintf('attribute type for field %s not exists', $field));
        }

        return $this->attributeType[$field];
    }

    /**
     * @return ArrayCollection|ConverterInterface[]
     */
    public function getFields(): ArrayCollection
    {
        return new ArrayCollection($this->fields);
    }

    /**
     * @return ArrayCollection|ConverterInterface[]
     */
    public function getAttributes(): ArrayCollection
    {
        return new ArrayCollection($this->attributes);
    }

    /**
     * @param TransformerCreatedEvent $event
     */
    protected function applyTransformerCreatedEvent(TransformerCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->key = $event->getKey();
        $this->name = $event->getName();
        $this->fields = [];
        $this->attributes = [];
        $this->attributeType = [];
        $this->multilingual = [];
    }

    /**
     * @param TransformerFieldAddedEvent $event
     */
    protected function applyTransformerFieldAddedEvent(TransformerFieldAddedEvent $event): void
    {
        $this->fields[$event->getField()] = $event->getConverter();
    }

    /**
     * @param TransformerAttributeAddedEvent $event
     */
    protected function applyTransformerAttributeAddedEvent(TransformerAttributeAddedEvent $event): void
    {
        $this->attributes[$event->getField()] = $event->getConverter();
        $this->attributeType[$event->getField()] = $event->getAttributeType();
        $this->multilingual[$event->getField()] = $event->isMultilingual();
    }
}
