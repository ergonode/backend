<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Domain\Condition;

use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use JMS\Serializer\Annotation as JMS;

class TextAttributeValueCondition implements ConditionInterface
{
    public const TYPE = 'TEXT_ATTRIBUTE_VALUE_CONDITION';
    public const PHRASE = 'TEXT_ATTRIBUTE_VALUE_CONDITION_PHRASE';

    public const HAS = 'HAS';
    public const IS_EQUAL = 'IS_EQUAL';

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $attribute;

    /**
     * @JMS\Type("string")
     */
    private string $operator;

    /**
     * @JMS\Type("string")
     */
    private string $value;

    public function __construct(AttributeId $attribute, string $operator, string $value)
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


    public function getAttribute(): AttributeId
    {
        return $this->attribute;
    }

    public function getOption(): string
    {
        return $this->operator;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
