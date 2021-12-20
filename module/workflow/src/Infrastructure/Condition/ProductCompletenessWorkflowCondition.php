<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Condition;

use Ergonode\Workflow\Domain\Condition\WorkflowConditionInterface;
use Webmozart\Assert\Assert;

class ProductCompletenessWorkflowCondition implements WorkflowConditionInterface
{
    public const TYPE = 'PRODUCT_COMPLETENESS_CONDITION';
    public const PHRASE = 'PRODUCT_COMPLETENESS_CONDITION_PHRASE';

    public const COMPLETE = 'complete';
    public const NOT_COMPLETE = 'not complete';

    public const OPTIONS = [
        self::COMPLETE,
        self::NOT_COMPLETE,
    ];

    public const PRODUCT_COMPLETE = 'PRODUCT_COMPLETE';
    public const PRODUCT_NOT_COMPLETE = 'PRODUCT_NOT_COMPLETE';

    private string $completeness;

    public function __construct(string $completeness)
    {
        Assert::oneOf($completeness, [self::COMPLETE, self::NOT_COMPLETE]);

        $this->completeness = $completeness;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getCompleteness(): string
    {
        return $this->completeness;
    }
}
