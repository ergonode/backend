<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Service;

use Ergonode\Condition\Domain\Condition\ConditionInterface;
use Ergonode\Condition\Domain\Entity\ConditionSet;
use Ergonode\Product\Domain\Entity\AbstractProduct;

/**
 */
class ConditionVerifier
{
    /**
     * @var ConditionVerifierStrategyInterface[]
     */
    private $strategies;

    /**
     * @param ConditionVerifierStrategyInterface ...$strategies
     */
    public function __construct(ConditionVerifierStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @param ConditionSet    $conditionSet
     * @param AbstractProduct $product
     *
     * @return bool
     */
    public function verify(ConditionSet $conditionSet, AbstractProduct $product): bool
    {
        foreach ($conditionSet->getConditions() as $condition) {
            $strategy = $this->getStrategy($condition);
            if (!$strategy->verify($product, $condition)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param ConditionInterface $condition
     *
     * @return ConditionVerifierStrategyInterface
     */
    private function getStrategy(ConditionInterface $condition): ConditionVerifierStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->isSupportedBy($condition->getType())) {
                return $strategy;
            }
        }

        throw new \RuntimeException(sprintf('Can\'t find strategy for "%s" condition', get_class($condition)));
    }
}
