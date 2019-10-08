<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Condition;

use Ergonode\Attribute\Domain\Entity\AttributeId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TextAttributeValueCondition implements ConditionInterface
{
    public const TYPE = 'TEXT_ATTRIBUTE_VALUE_CONDITION';
    public const PHRASE = 'TEXT_ATTRIBUTE_VALUE_CONDITION_PHRASE';

    /**
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\Attribute\Domain\Entity\AttributeId")
     */
    private $attribute;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $operator;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $value;

    /**
     * @param AttributeId $attribute
     * @param string      $operator
     * @param string      $value
     */
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
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
