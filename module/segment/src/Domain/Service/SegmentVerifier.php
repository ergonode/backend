<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Service;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Segment\Domain\Condition\ConditionInterface;
use Ergonode\Segment\Domain\Entity\Segment;

/**
 */
class SegmentVerifier
{
    /**
     * @var SegmentVerifierStrategyInterface[]
     */
    private $strategies;

    /**
     * @param SegmentVerifierStrategyInterface ...$strategies
     */
    public function __construct(SegmentVerifierStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @param Segment         $segment
     * @param AbstractProduct $product
     *
     * @return bool
     */
    public function verify(Segment $segment, AbstractProduct $product): bool
    {
        foreach ($segment->getConditions() as $condition) {
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
     * @return SegmentVerifierStrategyInterface
     */
    private function getStrategy(ConditionInterface $condition): SegmentVerifierStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->isSupportedBy($condition->getType())) {
                return $strategy;
            }
        }

        throw new \RuntimeException(sprintf('Can\'t find strategy for %s condition', get_class($condition)));
    }
}
