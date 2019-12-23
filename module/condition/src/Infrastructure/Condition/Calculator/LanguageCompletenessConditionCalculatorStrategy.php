<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Condition\Calculator;

use Ergonode\Completeness\Domain\Calculator\CompletenessCalculator;
use Ergonode\Condition\Domain\Condition\LanguageCompletenessCondition;
use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Condition\Infrastructure\Condition\ConditionCalculatorStrategyInterface;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Ergonode\Editor\Domain\Provider\DraftProvider;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Webmozart\Assert\Assert;

/**
 */
class LanguageCompletenessConditionCalculatorStrategy implements ConditionCalculatorStrategyInterface
{
    /**
     * @var CompletenessCalculator
     */
    private $calculator;

    /**
     * @var TemplateRepositoryInterface
     */
    private $repository;

    /**
     * @var DraftProvider
     */
    private $provider;

    /**
     * @param CompletenessCalculator      $calculator
     * @param TemplateRepositoryInterface $repository
     * @param DraftProvider               $provider
     */
    public function __construct(
        CompletenessCalculator $calculator,
        TemplateRepositoryInterface $repository,
        DraftProvider $provider
    ) {
        $this->calculator = $calculator;
        $this->repository = $repository;
        $this->provider = $provider;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return LanguageCompletenessCondition::TYPE === $type;
    }

    /**
     * @param AbstractProduct                                  $object
     * @param ConditionInterface|LanguageCompletenessCondition $configuration
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function calculate(AbstractProduct $object, ConditionInterface $configuration): bool
    {
        $draft = $this->provider->provide($object);
        $template = $this->repository->load($object->getTemplateId());
        Assert::notNull($template, sprintf('Can\'t find template "%s"', $object->getTemplateId()->getValue()));

        $calculation = $this->calculator->calculate($draft, $template, $configuration->getLanguage());

        $result = true;

        if ($configuration->getCompleteness() === LanguageCompletenessCondition::COMPLETE) {
            if ($calculation->getPercent() < 100) {
                $result = false;
            }
        } else {
            if ($calculation->getPercent() > 0) {
                $result = false;
            }
        }

        return $result;
    }
}
