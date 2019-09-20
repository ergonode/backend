<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Transformer\Infrastructure\Converter\ConverterInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TransformerConverterAddedEvent implements DomainEventInterface
{
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
     * @param string             $collection
     * @param string             $field
     * @param ConverterInterface $converter
     */
    public function __construct(string $collection, string $field, ConverterInterface $converter)
    {
        $this->collection = $collection;
        $this->field = $field;
        $this->converter = $converter;
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
