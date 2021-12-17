<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Provider;

use Ergonode\Workflow\Domain\Condition\WorkflowConditionValidatorInterface;
use Webmozart\Assert\Assert;

class WorkflowConditionValidatorProvider
{
    /**
     * @var WorkflowConditionValidatorInterface[] $strategies
     */
    private iterable $strategies;

    public function __construct(iterable ...$strategies)
    {
        Assert::allIsInstanceOf($strategies, WorkflowConditionValidatorInterface::class);

        $this->strategies = $strategies;
    }

    /**
     * @throws \OutOfBoundsException
     */
    public function provide(string $type): WorkflowConditionValidatorInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($type)) {
                return $strategy;
            }
        }

        throw new \OutOfBoundsException(sprintf('Constraint by condition type "%s" not found', $type));
    }
}
