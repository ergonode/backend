<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Service;

use Ergonode\Condition\Domain\Condition\ConditionInterface;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class ConditionConfigurator
{
    /**
     * @var ConfigurationStrategyInterface[]
     */
    private $strategies;

    /**
     * @param ConfigurationStrategyInterface ...$strategies
     */
    public function __construct(ConfigurationStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @param ConditionInterface $condition
     * @param Language           $language
     *
     * @return array
     */
    public function getConfiguration(ConditionInterface $condition, Language $language): array
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->isSupportedBy($condition->getType())) {
                return $strategy->getConfiguration($language);
            }
        }

        throw new \RuntimeException(sprintf('Can\'t find strategy for "%s" condition', get_class($condition)));
    }
}
