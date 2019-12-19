<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Domain\Event;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainAggregateEventInterface;
use Ergonode\Transformer\Domain\Entity\TransformerId;
use Ergonode\Transformer\Infrastructure\Converter\ConverterInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TransformerConverterAddedEvent implements DomainAggregateEventInterface
{
    /**
     * @var TransformerId
     *
     * @JMS\Type("Ergonode\Transformer\Domain\Entity\TransformerId")
     */
    private $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $collection;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $field;

    /**
     * @var ConverterInterface
     *
     * @JMS\Type("Ergonode\Transformer\Infrastructure\Converter\ConverterInterface")
     */
    private $converter;

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
     * @return AbstractId|TransformerId
     */
    public function getAggregateId(): AbstractId
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
