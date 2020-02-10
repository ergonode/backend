<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Condition;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Condition\Domain\ConditionInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class NumericAttributeValueCondition implements ConditionInterface
{
    public const TYPE = 'NUMERIC_ATTRIBUTE_VALUE_CONDITION';
    public const PHRASE = 'NUMERIC_ATTRIBUTE_VALUE_CONDITION_PHRASE';

    /**
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $attribute;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $operator;

    /**
     * @var float
     *
     * @JMS\Type("float")
     */
    private float $value;

    /**
     * @param AttributeId $attribute
     * @param string      $operator
     * @param float       $value
     */
    public function __construct(AttributeId $attribute, string $operator, float $value)
    {
        $this->attribute = $attribute;
        $this->operator = $operator;
        $this->value = $value;
    }

    /**
     * {@inheritDoc}
     *
     * @JMS\VirtualProperty()
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return AttributeId
     */
    public function getAttribute(): AttributeId
    {
        return $this->attribute;
    }

    /**
     * @return string
     */
    public function getOption(): string
    {
        return $this->operator;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }
}
