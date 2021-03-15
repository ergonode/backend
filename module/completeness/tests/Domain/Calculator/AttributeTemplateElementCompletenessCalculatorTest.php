<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Tests\Domain\Calculator;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Infrastructure\Calculator\TranslationInheritanceCalculator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Designer\Domain\Entity\Element\AttributeTemplateElement;
use Ergonode\Completeness\Domain\Calculator\AttributeTemplateElementCompletenessCalculator;

class AttributeTemplateElementCompletenessCalculatorTest extends TestCase
{
    /**
     * @var AttributeRepositoryInterface|MockObject
     */
    private AttributeRepositoryInterface $repository;

    /**
     * @var TranslationInheritanceCalculator|MockObject
     */
    private TranslationInheritanceCalculator $calculator;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(AttributeRepositoryInterface::class);
        $this->calculator = $this->createMock(TranslationInheritanceCalculator::class);
    }

    public function testSupport(): void
    {
        $strategy = new AttributeTemplateElementCompletenessCalculator($this->repository, $this->calculator);
        $this::assertTrue($strategy->supports(AttributeTemplateElement::TYPE));
        $this::assertFalse($strategy->supports('Any other variant'));
    }

    public function testGetElementCompletenessForNotExistsAttribute(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $product = $this->createMock(AbstractProduct::class);
        $language = $this->createMock(Language::class);
        $element = $this->createMock(AttributeTemplateElement::class);
        $element->method('isRequired')->willReturn(true);

        $strategy = new AttributeTemplateElementCompletenessCalculator($this->repository, $this->calculator);
        $strategy->calculate($product, $language, $element);
    }

    public function testGetElementCompleteness(): void
    {
        $attribute = $this->createMock(AbstractAttribute::class);
        $this->repository->method('load')->willReturn($attribute);
        $product = $this->createMock(AbstractProduct::class);
        $product->expects(self::once())->method('hasAttribute')->willReturn(true);
        $language = $this->createMock(Language::class);
        $element = $this->createMock(AttributeTemplateElement::class);
        $element->method('isRequired')->willReturn(true);

        $strategy = new AttributeTemplateElementCompletenessCalculator($this->repository, $this->calculator);
        $strategy->calculate($product, $language, $element);
    }
}
