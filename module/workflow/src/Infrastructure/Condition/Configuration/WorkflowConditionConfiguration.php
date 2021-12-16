<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Condition\Configuration;

use Webmozart\Assert\Assert;

class WorkflowConditionConfiguration
{
    private string $name;

    private string $phrase;

    /**
     * @var WorkflowConditionConfigurationParameterInterface[]
     */
    private array $parameters;

    public function __construct(string $name, string $phrase, array $parameters)
    {
        Assert::allIsInstanceOf($parameters, WorkflowConditionConfigurationParameterInterface::class);

        $this->name = $name;
        $this->phrase = $phrase;
        $this->parameters = $parameters;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPhrase(): string
    {
        return $this->phrase;
    }

    /**
     * @return WorkflowConditionConfigurationParameterInterface[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
