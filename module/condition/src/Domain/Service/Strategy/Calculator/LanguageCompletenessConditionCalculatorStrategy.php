<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Service\Strategy\Calculator;

use Ergonode\Completeness\Domain\Calculator\CompletenessCalculator;
use Ergonode\Condition\Domain\Condition\ConditionInterface;
use Ergonode\Condition\Domain\Condition\LanguageCompletenessCondition;
use Ergonode\Condition\Domain\Service\ConditionCalculatorStrategyInterface;
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
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function calculate(AbstractProduct $object, ConditionInterface $configuration): bool
    {
        $draft = $this->provider->provide($object);
        $template = $this->repository->load($object->getTemplateId());
        Assert::notNull($template, sprintf('Can\'t find template "%s"', $object->getTemplateId()->getValue()));

        $result = $this->calculator->calculate($draft, $template, $configuration->getLanguage());

        return 100 === (int) $result->getPercent();
    }
}
