<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Condition\Calculator;

use Ergonode\Completeness\Infrastructure\Persistence\Query\CompletenessQuery;
use Ergonode\Condition\Domain\Condition\ProductCompletenessCondition;
use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Condition\Infrastructure\Condition\ConditionCalculatorStrategyInterface;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;

class ProductCompletenessConditionCalculatorStrategy implements ConditionCalculatorStrategyInterface
{
    private LanguageQueryInterface $query;

    private CompletenessQuery $completenessQuery;

    public function __construct(
        LanguageQueryInterface $query,
        CompletenessQuery $completenessQuery
    ) {
        $this->query = $query;
        $this->completenessQuery = $completenessQuery;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return ProductCompletenessCondition::TYPE === $type;
    }

    /**
     * @param ConditionInterface|ProductCompletenessCondition $configuration
     *
     * @throws \Exception
     */
    public function calculate(AbstractProduct $object, ConditionInterface $configuration): bool
    {
        if (!$configuration instanceof ProductCompletenessCondition) {
            throw new \LogicException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    ProductCompletenessCondition::class,
                    get_debug_type($configuration)
                )
            );
        }
        $result = true;

        foreach ($this->query->getActive() as $code) {
            $calculation = $this->completenessQuery->hasCompleteness($object->getId(), $code);
            if ($configuration->getCompleteness() === ProductCompletenessCondition::COMPLETE) {
                if (!$calculation) {
                    $result = false;
                }
            } elseif ($calculation) {
                $result = false;
            }
        }

        return $result;
    }
}
