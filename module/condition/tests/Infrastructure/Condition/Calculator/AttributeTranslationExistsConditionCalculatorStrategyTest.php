<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Tests\Infrastructure\Condition\Calculator;

use PHPUnit\Framework\TestCase;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Product\Infrastructure\Calculator\TranslationInheritanceCalculator;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Condition\Domain\Condition\AttributeTranslationExistsCondition;
use Ergonode\Condition\Infrastructure\Condition\Calculator\AttributeTranslationExistsConditionCalculatorStrategy;

class AttributeTranslationExistsConditionCalculatorStrategyTest extends TestCase
{
    private AttributeRepositoryInterface $repository;

    private TranslationInheritanceCalculator $calculator;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(AttributeRepositoryInterface::class);
        $this->calculator = $this->createMock(TranslationInheritanceCalculator::class);
    }

    public function testSupports(): void
    {
        $strategy = new AttributeTranslationExistsConditionCalculatorStrategy($this->repository, $this->calculator);
        self::assertTrue($strategy->supports('ATTRIBUTE_TRANSLATION_EXISTS_CONDITION'));
        self::assertFalse($strategy->supports('test'));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testCalculate(bool $hasAttribute, Language $language, ?string $value, bool $result): void
    {
        $object = $this->createMock(AbstractProduct::class);
        $configuration = $this->createMock(AttributeTranslationExistsCondition::class);
        $configuration
            ->expects(self::once())
            ->method('getAttribute')
            ->willReturn($this->createMock(AttributeId::class));
        $configuration->expects(self::once())->method('getLanguage')->willReturn($language);

        $this
            ->repository
            ->expects(self::once())
            ->method('load')
            ->willReturn($this->createMock(AbstractAttribute::class));

        $object->expects(self::once())->method('hasAttribute')->willReturn($hasAttribute);
        $object->method('getAttribute')
            ->willReturn($this->createMock(ValueInterface::class));

        $this->calculator->method('calculate')->willReturn($value);

        $strategy = new AttributeTranslationExistsConditionCalculatorStrategy($this->repository, $this->calculator);
        self::assertSame($result, $strategy->calculate($object, $configuration));
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'hasAttribute' => false,
                'language' => new Language('pl_PL'),
                'value' => 'value',
                'result' => false,
            ],
            [
                'hasAttribute' => true,
                'language' => new Language('pl_PL'),
                'value' => 'value',
                'result' => true,
            ],
            [
                'hasAttribute' => true,
                'language' => new Language('pl_PL'),
                'value' => null,
                'result' => false,
            ],
        ];
    }
}
