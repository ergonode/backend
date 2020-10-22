<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Condition\Calculator;

use Ergonode\Completeness\Domain\Calculator\CompletenessCalculator;
use Ergonode\Condition\Domain\Condition\ProductCompletenessCondition;
use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Condition\Infrastructure\Condition\ConditionCalculatorStrategyInterface;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Ergonode\Editor\Domain\Provider\DraftProvider;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Webmozart\Assert\Assert;

class ProductCompletenessConditionCalculatorStrategy implements ConditionCalculatorStrategyInterface
{
    /**
     * @var CompletenessCalculator
     */
    private CompletenessCalculator $calculator;

    /**
     * @var TemplateRepositoryInterface
     */
    private TemplateRepositoryInterface $repository;

    /**
     * @var LanguageQueryInterface
     */
    private LanguageQueryInterface $query;

    /**
     * @var DraftProvider
     */
    private DraftProvider $provider;

    /**
     * @param CompletenessCalculator      $calculator
     * @param TemplateRepositoryInterface $repository
     * @param LanguageQueryInterface      $query
     * @param DraftProvider               $provider
     */
    public function __construct(
        CompletenessCalculator $calculator,
        TemplateRepositoryInterface $repository,
        LanguageQueryInterface $query,
        DraftProvider $provider
    ) {
        $this->calculator = $calculator;
        $this->repository = $repository;
        $this->query = $query;
        $this->provider = $provider;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return ProductCompletenessCondition::TYPE === $type;
    }

    /**
     * @param AbstractProduct                                 $object
     * @param ConditionInterface|ProductCompletenessCondition $configuration
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function calculate(AbstractProduct $object, ConditionInterface $configuration): bool
    {
        $draft = $this->provider->provide($object);

        $templateId = $object->getTemplateId();

        $template = $this->repository->load($templateId);
        Assert::notNull($template, sprintf('Can\'t find template %s', $templateId->getValue()));

        $result = true;

        foreach ($this->query->getActive() as $code) {
            $calculation = $this->calculator->calculate($draft, $template, $code);
            if ($configuration->getCompleteness() === ProductCompletenessCondition::COMPLETE) {
                if ($calculation->getPercent() < 100) {
                    $result = false;
                }
            } elseif ($calculation->getPercent() === 100) {
                $result = false;
            }
        }

        return $result;
    }
}
