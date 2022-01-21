<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Provider;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Workflow\Domain\Condition\WorkflowConditionConfigurationInterface;
use Ergonode\Workflow\Domain\Condition\Configuration\WorkflowConditionConfiguration;
use Webmozart\Assert\Assert;

class WorkflowConditionConfigurationProvider
{
    /**
     * @var WorkflowConditionConfigurationInterface[]
     */
    private iterable $strategies;

    /**
     * @param WorkflowConditionConfigurationInterface[] $strategies
     */
    public function __construct(iterable $strategies)
    {
        Assert::allIsInstanceOf($strategies, WorkflowConditionConfigurationInterface::class);

        $this->strategies = $strategies;
    }

    public function provide(Language $language, string $type): WorkflowConditionConfiguration
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($type)) {
                return $strategy->getConfiguration($language);
            }
        }

        throw new \RuntimeException($type);
    }
}
