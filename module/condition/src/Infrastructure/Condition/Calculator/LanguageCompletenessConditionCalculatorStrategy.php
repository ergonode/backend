<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Condition\Calculator;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Completeness\Domain\Calculator\CompletenessCalculator;
use Ergonode\Condition\Domain\Condition\LanguageCompletenessCondition;
use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Condition\Infrastructure\Condition\ConditionCalculatorStrategyInterface;
use Ergonode\Designer\Domain\Entity\Attribute\TemplateSystemAttribute;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
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
    private CompletenessCalculator $calculator;

    /**
     * @var TemplateRepositoryInterface
     */
    private TemplateRepositoryInterface $repository;

    /**
     * @var DraftProvider
     */
    private DraftProvider $provider;

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
        $templateId = new TemplateId(
            $object->getAttribute(
                new AttributeCode(
                    TemplateSystemAttribute::CODE
                )
            )->getValue()
        );

        $template = $this->repository->load($templateId);
        Assert::notNull($template, sprintf('Can\'t find template "%s"', $templateId->getValue()));

        $calculation = $this->calculator->calculate($draft, $template, $configuration->getLanguage());

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
