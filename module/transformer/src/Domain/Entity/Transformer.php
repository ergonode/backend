<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */
declare(strict_types = 1);

namespace Ergonode\Transformer\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Transformer\Domain\Event\TransformerConverterAddedEvent;
use Ergonode\Transformer\Domain\Event\TransformerCreatedEvent;
use Ergonode\Transformer\Infrastructure\Converter\ConverterInterface;

/**
 */
class Transformer extends AbstractAggregateRoot
{
    private const DEFAULT = '__default';

    /**
     * @var TransformerId
     */
    private $id;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $name;

    /**
     * @var ConverterInterface[]
     */
    private $converters;

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
    public function getId(): AbstractId
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
     * @param string             $collection
     *
     * @return $this
     * @throws \Exception
     */
    public function addConverter(string $field, ConverterInterface $converter, string $collection = self::DEFAULT): self
    {
        if ($this->hasConverter($field, $collection)) {
            throw new \InvalidArgumentException(sprintf('converter for field %s already exists', $field));
        }

        $this->apply(new TransformerConverterAddedEvent($collection, $field, $converter));

        return $this;
    }

    /**
     * @param string $field
     * @param string $collection
     *
     * @return bool
     */
    public function hasConverter(string $field, string $collection = self::DEFAULT): bool
    {
        return isset($this->converters[$collection][$field]);
    }

    /**
     * @return ArrayCollection|ConverterInterface[]
     */
    public function getConverters(): ArrayCollection
    {
        return new ArrayCollection($this->converters);
    }

    /**
     * @param TransformerCreatedEvent $event
     */
    protected function applyTransformerCreatedEvent(TransformerCreatedEvent $event): void
    {
        $this->id = $event->getId();
        $this->key = $event->getKey();
        $this->name = $event->getName();
        $this->converters = [];
    }

    /**
     * @param TransformerConverterAddedEvent $event
     */
    protected function applyTransformerConverterAddedEvent(TransformerConverterAddedEvent $event): void
    {
        $this->converters[$event->getCollection()][$event->getField()] = $event->getConverter();
    }
}
