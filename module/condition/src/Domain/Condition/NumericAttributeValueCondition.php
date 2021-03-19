<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Domain\Condition;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Condition\Domain\ConditionInterface;

class NumericAttributeValueCondition implements ConditionInterface
{
    public const TYPE = 'NUMERIC_ATTRIBUTE_VALUE_CONDITION';
    public const PHRASE = 'NUMERIC_ATTRIBUTE_VALUE_CONDITION_PHRASE';

    private AttributeId $attribute;

    private string $operator;

    private float $value;

    public function __construct(AttributeId $attribute, string $operator, float $value)
    {
        $this->attribute = $attribute;
        $this->operator = $operator;
        $this->value = $value;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getAttribute(): AttributeId
    {
        return $this->attribute;
    }

    public function getOption(): string
    {
        return $this->operator;
    }

    public function getValue(): float
    {
        return $this->value;
    }
}
