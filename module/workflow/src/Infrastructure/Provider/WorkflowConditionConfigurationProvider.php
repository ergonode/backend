<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Provider;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Workflow\Domain\Condition\WorkflowConditionConfigurationInterface;
use Ergonode\Workflow\Domain\Condition\Configuration\WorkflowConditionConfiguration;

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
        $this->strategies = $strategies;
    }

    public function getConfiguration(Language $language, string $type): WorkflowConditionConfiguration
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($type)) {
                return $strategy->getConfiguration($language);
            }
        }

        throw new \RuntimeException($type);
    }
}
