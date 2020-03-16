<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Service;

use Ergonode\Condition\Domain\Repository\ConditionSetRepositoryInterface;
use Ergonode\Condition\Domain\Service\ConditionCalculator;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Workflow\Domain\Entity\Transition;
use Webmozart\Assert\Assert;

/**
 */
class StatusCalculationService
{
    /**
     * @var ConditionCalculator
     */
    private ConditionCalculator $service;

    /**
     * @var ConditionSetRepositoryInterface
     */
    private ConditionSetRepositoryInterface $repository;

    /**
     * @param ConditionCalculator             $service
     * @param ConditionSetRepositoryInterface $repository
     */
    public function __construct(ConditionCalculator $service, ConditionSetRepositoryInterface $repository)
    {
        $this->service = $service;
        $this->repository = $repository;
    }

    /**
     * @param Transition      $transition
     * @param AbstractProduct $product
     *
     * @return bool
     */
    public function available(Transition $transition, AbstractProduct $product): bool
    {
        if (null === $transition->getConditionSetId()) {
            return true;
        }

        $conditionSet = $this->repository->load($transition->getConditionSetId());
        Assert::notNull($conditionSet);

        return $this->service->calculate($conditionSet, $product);
    }
}
