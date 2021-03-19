<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Domain\Condition;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Condition\Domain\ConditionInterface;

class AttributeExistsCondition implements ConditionInterface
{
    public const TYPE = 'ATTRIBUTE_EXISTS_CONDITION';
    public const PHRASE = 'ATTRIBUTE_EXISTS_CONDITION_PHRASE';

    private AttributeId $attribute;

    public function __construct(AttributeId $attribute)
    {
        $this->attribute = $attribute;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getAttribute(): AttributeId
    {
        return $this->attribute;
    }
}
