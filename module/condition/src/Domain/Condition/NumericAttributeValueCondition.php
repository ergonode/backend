<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Condition;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use JMS\Serializer\Annotation as JMS;

/**
 */
class NumericAttributeValueCondition implements ConditionInterface
{
    public const TYPE = 'NUMERIC_ATTRIBUTE_VALUE_CONDITION';
    public const PHRASE = 'NUMERIC_ATTRIBUTE_VALUE_CONDITION_PHRASE';

    /**
     * @var AttributeCode
     *
     * @JMS\Type("Ergonode\Component\Attribute\Domain\ValueObject\AttributeCode")
     */
    private $code;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $value;

    /**
     * @param AttributeCode $code
     * @param string        $value
     */
    public function __construct(AttributeCode $code, string $value)
    {
        $this->code = $code;
        $this->value = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return AttributeCode
     */
    public function getAttribute(): AttributeCode
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
