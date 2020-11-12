<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Condition\Calculator;

use Ergonode\Completeness\Infrastructure\Persistence\Query\CompletenessQuery;
use Ergonode\Condition\Domain\Condition\LanguageCompletenessCondition;
use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Condition\Infrastructure\Condition\ConditionCalculatorStrategyInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;

class LanguageCompletenessConditionCalculatorStrategy implements ConditionCalculatorStrategyInterface
{
    private CompletenessQuery $completenessQuery;

    public function __construct(
        CompletenessQuery $completenessQuery
    ) {
        $this->completenessQuery = $completenessQuery;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return LanguageCompletenessCondition::TYPE === $type;
    }

    /**
     * @throws \Exception
     */
    public function calculate(AbstractProduct $object, ConditionInterface $configuration): bool
    {
        if (!$configuration instanceof LanguageCompletenessCondition) {
            throw new \LogicException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    LanguageCompletenessCondition::class,
                    get_debug_type($configuration)
                )
            );
        }
        $calculation = $this->completenessQuery->getCompleteness($object->getId(), $configuration->getLanguage());

        $result = true;

        if ($configuration->getCompleteness() === LanguageCompletenessCondition::COMPLETE) {
            if ($calculation->getPercent() < 100) {
                $result = false;
            }
        } elseif ($calculation->getPercent() === 100) {
            $result = false;
        }

        return $result;
    }
}
