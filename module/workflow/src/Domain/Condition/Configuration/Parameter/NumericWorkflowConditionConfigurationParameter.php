<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Condition\Configuration\Parameter;

use Ergonode\Workflow\Domain\Condition\Configuration\WorkflowConditionConfigurationParameterInterface;

class NumericWorkflowConditionConfigurationParameter implements WorkflowConditionConfigurationParameterInterface
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
