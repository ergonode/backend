<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;
use Ergonode\Transformer\Infrastructure\Converter\ConverterInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TransformerConverterAddedEvent implements DomainEventInterface
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
    private string $collection;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $field;

    /**
     * @var ConverterInterface
     *
     * @JMS\Type("Ergonode\Transformer\Infrastructure\Converter\ConverterInterface")
     */
    private ConverterInterface $converter;

    /**
     * @param TransformerId      $id
     * @param string             $collection
     * @param string             $field
     * @param ConverterInterface $converter
     */
    public function __construct(TransformerId $id, string $collection, string $field, ConverterInterface $converter)
    {
        $this->id = $id;
        $this->collection = $collection;
        $this->field = $field;
        $this->converter = $converter;
    }

    /**
     * @return TransformerId
     */
    public function getAggregateId(): TransformerId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCollection(): string
    {
        return $this->collection;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return ConverterInterface
     */
    public function getConverter(): ConverterInterface
    {
        return $this->converter;
    }
}
