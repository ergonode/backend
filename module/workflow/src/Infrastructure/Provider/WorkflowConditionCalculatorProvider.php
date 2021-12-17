<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Provider;

use Ergonode\Workflow\Domain\Condition\WorkflowConditionCalculatorInterface;
use Ergonode\Workflow\Domain\Condition\WorkflowConditionInterface;
use Webmozart\Assert\Assert;

class WorkflowConditionCalculatorProvider
{
    /**
     * @var WorkflowConditionCalculatorInterface[]
     */
    private iterable $strategies;

    /**
     * @param WorkflowConditionCalculatorInterface[] $strategies
     */
    public function __construct(iterable $strategies)
    {
        Assert::allIsInstanceOf($strategies, WorkflowConditionCalculatorInterface::class);

        $this->strategies = $strategies;
    }

    public function provide(WorkflowConditionInterface $condition): WorkflowConditionCalculatorInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($condition)) {
                return $strategy;
            }
        }

        throw new \RuntimeException($condition);
    }
}
