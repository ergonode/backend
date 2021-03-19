<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Domain\Condition;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Condition\Domain\ConditionInterface;

class OptionAttributeValueCondition implements ConditionInterface
{
    public const TYPE = 'OPTION_ATTRIBUTE_VALUE_CONDITION';
    public const PHRASE = 'OPTION_ATTRIBUTE_VALUE_CONDITION_PHRASE';

    private AttributeId $attribute;

    private string $value;

    public function __construct(AttributeId $attribute, string $value)
    {
        $this->attribute = $attribute;
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

    public function getValue(): string
    {
        return $this->value;
    }
}
