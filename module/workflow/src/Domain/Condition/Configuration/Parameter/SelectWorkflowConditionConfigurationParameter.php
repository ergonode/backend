<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Condition\Configuration\Parameter;

use Ergonode\Workflow\Domain\Condition\Configuration\WorkflowConditionConfigurationParameterInterface;

class SelectWorkflowConditionConfigurationParameter implements WorkflowConditionConfigurationParameterInterface
{
    private string $name;

    private array $options;

    /**
     * @param array<string, string> $options
     */
    public function __construct(string $name, array $options)
    {
        $this->name = $name;
        $this->options = $options;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<string, string>
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
