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
class AttributeExistsCondition implements ConditionInterface
{
    public const TYPE = 'ATTRIBUTE_EXISTS_CONDITION';
    public const PHRASE = 'ATTRIBUTE_EXISTS_CONDITION_PHRASE';

    /**
     * @var AttributeCode
     *
     * @JMS\Type("Ergonode\Component\Attribute\Domain\ValueObject\AttributeCode")
     */
    private $attribute;

    /**
     * @param AttributeCode $attribute
     */
    public function __construct(AttributeCode $attribute)
    {
        $this->attribute = $attribute;
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
    public function getCode(): AttributeCode
    {
        return $this->attribute;
    }
}
