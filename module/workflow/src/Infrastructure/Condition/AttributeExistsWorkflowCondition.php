<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Condition;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Workflow\Domain\Condition\WorkflowConditionInterface;

class AttributeExistsWorkflowCondition implements WorkflowConditionInterface
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
