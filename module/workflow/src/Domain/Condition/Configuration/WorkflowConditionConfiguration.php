<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Condition\Configuration;

use Webmozart\Assert\Assert;

class WorkflowConditionConfiguration
{
    private string $name;

    private string $phrase;

    private string $type;

    /**
     * @var WorkflowConditionConfigurationParameterInterface[]
     */
    private array $parameters;

    public function __construct(string $type, string $name, string $phrase, array $parameters)
    {
        Assert::allIsInstanceOf($parameters, WorkflowConditionConfigurationParameterInterface::class);

        $this->type = $type;
        $this->name = $name;
        $this->phrase = $phrase;
        $this->parameters = $parameters;
    }

    public function getType(): string
    {
        return $this->type;
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
