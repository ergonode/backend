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

class TransformerAttributeAddedEvent implements DomainEventInterface
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
    private string $field;

    /**
     * @var ConverterInterface
     *
     * @JMS\Type("Ergonode\Transformer\Infrastructure\Converter\ConverterInterface")
     */
    private ConverterInterface $converter;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $attributeType;

    /**
     * @var bool
     *
     * @JMS\Type("bool")
     */
    private bool $multilingual;

    /**
     * @param TransformerId      $id
     * @param string             $field
     * @param ConverterInterface $converter
     * @param string             $attributeType
     * @param bool               $multilingual
     */
    public function __construct(
        TransformerId $id,
        string $field,
        ConverterInterface $converter,
        string $attributeType,
        bool $multilingual = true
    ) {
        $this->id = $id;
        $this->field = $field;
        $this->converter = $converter;
        $this->attributeType = $attributeType;
        $this->multilingual = $multilingual;
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

    /**
     * @return string
     */
    public function getAttributeType(): string
    {
        return $this->attributeType;
    }

    /**
     * @return bool
     */
    public function isMultilingual(): bool
    {
        return $this->multilingual;
    }
}
