<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Tests\Infrastructure\Condition\Calculator;

use Ergonode\Workflow\Infrastructure\Condition\Calculator\AttributeExistsWorkflowConditionCalculator;
use PHPUnit\Framework\TestCase;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Product\Infrastructure\Calculator\TranslationInheritanceCalculator;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Workflow\Infrastructure\Condition\AttributeExistsWorkflowCondition;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

class AttributeExistsWorkflowConditionCalculatorTest extends TestCase
{
    private AttributeRepositoryInterface $attributeRepository;

    private TranslationInheritanceCalculator $inheritanceCalculator;

    private LanguageQueryInterface $languageQuery;

    private AbstractProduct $product;

    private AttributeExistsWorkflowCondition $workflowCondition;

    protected function setUp(): void
    {
        $attribute = $this->createMock(AbstractAttribute::class);
        $this->attributeRepository = $this->createMock(AttributeRepositoryInterface::class);
        $this->attributeRepository->method('load')->willReturn($attribute);
        $this->inheritanceCalculator = $this->createMock(TranslationInheritanceCalculator::class);
        $this->languageQuery = $this->createMock(LanguageQueryInterface::class);
        $this->product = $this->createMock(AbstractProduct::class);
        $this->workflowCondition = $this->createMock(AttributeExistsWorkflowCondition::class);
    }

    public function testNoAttributesExists(): void
    {
        $language = $this->createMock(Language::class);

        $calculator = new AttributeExistsWorkflowConditionCalculator(
            $this->attributeRepository,
            $this->inheritanceCalculator,
            $this->languageQuery
        );

        $result = $calculator->calculate($this->product, $this->workflowCondition, $language);

        self::assertFalse($result);
    }

    public function testValueExists(): void
    {
        $language = $this->createMock(Language::class);
        $value = $this->createMock(ValueInterface::class);
        $value->expects(self::once())->method('hasTranslation')->willReturn(true);

        $this->product->method('hasAttribute')->willReturn(true);
        $this->product->method('getAttribute')->willReturn($value);

        $calculator = new AttributeExistsWorkflowConditionCalculator(
            $this->attributeRepository,
            $this->inheritanceCalculator,
            $this->languageQuery
        );

        $result = $calculator->calculate($this->product, $this->workflowCondition, $language);

        self::assertTrue($result);
    }

    public function testCalculatedValueExists(): void
    {
        $language = $this->createMock(Language::class);
        $value = $this->createMock(ValueInterface::class);
        $value->expects(self::once())->method('hasTranslation')->willReturn(false);
        $this->inheritanceCalculator->expects(self::once())->method('calculate')->willReturn(true);

        $this->product->method('hasAttribute')->willReturn(true);
        $this->product->method('getAttribute')->willReturn($value);


        $calculator = new AttributeExistsWorkflowConditionCalculator(
            $this->attributeRepository,
            $this->inheritanceCalculator,
            $this->languageQuery
        );

        $result = $calculator->calculate($this->product, $this->workflowCondition, $language);

        self::assertTrue($result);
    }
}
