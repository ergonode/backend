<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Provider;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Workflow\Domain\Condition\WorkflowConditionConfigurationInterface;

class WorkflowConditionDictionaryProvider
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

    public function getDictionary(Language $language): array
    {
        $result = [];
        foreach ($this->strategies as $strategy) {
            $type = $strategy->getConfiguration($language)->getType();
            $result[$type] = $strategy->getConfiguration($language)->getName();
        }

        return $result;
    }
}
