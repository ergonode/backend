<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;
use Ergonode\Importer\Domain\Event\TransformerCreatedEvent;
use Ergonode\Importer\Infrastructure\Converter\ConverterInterface;
use Ergonode\Importer\Domain\Event\TransformerFieldAddedEvent;
use Ergonode\Importer\Domain\Event\TransformerAttributeAddedEvent;
use JMS\Serializer\Annotation as JMS;

class Transformer extends AbstractAggregateRoot
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TransformerId")
     */
    private TransformerId $id;

    /**
     * @JMS\Type("string")
     */
    private string $key;

    /**
     * @JMS\Type("string")
     */
    private string $name;

    /**
     * @var ConverterInterface[]
     *
     * @JMS\Type("array<string, Ergonode\Importer\Infrastructure\Converter\ConverterInterface>")
     */
    private array $fields;

    /**
     * @var ConverterInterface[]
     *
     * @JMS\Type("array<string, Ergonode\Importer\Infrastructure\Converter\ConverterInterface>")
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
     * @throws \Exception
     */
    public function __construct(TransformerId $id, string $name, string $key)
    {
        $this->apply(new TransformerCreatedEvent($id, $name, $key));
    }

    public function getId(): TransformerId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    /**
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

    public function hasField(string $field): bool
    {
        return isset($this->fields[$field]);
    }

    public function hasAttribute(string $field): bool
    {
        return isset($this->attributes[$field]);
    }

    public function isAttributeMultilingual(string $field): bool
    {
        if (!$this->hasAttribute($field)) {
            throw new \InvalidArgumentException(sprintf('attribute multilingual %s not exists', $field));
        }

        return $this->multilingual[$field];
    }

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

    protected function applyTransformerFieldAddedEvent(TransformerFieldAddedEvent $event): void
    {
        $this->fields[$event->getField()] = $event->getConverter();
    }

    protected function applyTransformerAttributeAddedEvent(TransformerAttributeAddedEvent $event): void
    {
        $this->attributes[$event->getField()] = $event->getConverter();
        $this->attributeType[$event->getField()] = $event->getAttributeType();
        $this->multilingual[$event->getField()] = $event->isMultilingual();
    }
}
