<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Service;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Segment\Domain\Condition\ConditionInterface;

/**
 */
class SegmentConditionConfigurator
{
    /**
     * @var SegmentConfigurationStrategyInterface[]
     */
    private $strategies;

    /**
     * @param SegmentConfigurationStrategyInterface ...$strategies
     */
    public function __construct(SegmentConfigurationStrategyInterface ...$strategies)
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

        throw new \RuntimeException(sprintf('Can\'t find strategy for %s condition', get_class($condition)));
    }
}
