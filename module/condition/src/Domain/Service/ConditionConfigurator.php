<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Domain\Service;

use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Condition\Infrastructure\Condition\ConditionConfigurationStrategyInterface;
use Ergonode\Core\Domain\ValueObject\Language;

class ConditionConfigurator
{
    /**
     * @var ConditionConfigurationStrategyInterface[]
     */
    private array $strategies;

    public function __construct(ConditionConfigurationStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @return array
     */
    public function getConfiguration(ConditionInterface $condition, Language $language): array
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($condition->getType())) {
                return $strategy->getConfiguration($language);
            }
        }

        throw new \RuntimeException(sprintf('Can\'t find strategy for "%s" condition', get_class($condition)));
    }
}
