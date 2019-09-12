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
class OptionAttributeValueCondition implements ConditionInterface
{
    public const TYPE = 'OPTION_ATTRIBUTE_VALUE_CONDITION';
    public const PHRASE = 'OPTION_ATTRIBUTE_VALUE_CONDITION_PHRASE';

    /**
     * @var AttributeCode
     *
     * @JMS\Type("Ergonode\Component\Attribute\Domain\ValueObject\AttributeCode")
     */
    private $attribute;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $value;

    /**
     * @param AttributeCode $attribute
     * @param string        $value
     */
    public function __construct(AttributeCode $attribute, string $value)
    {
        $this->attribute = $attribute;
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
        return $this->attribute;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
