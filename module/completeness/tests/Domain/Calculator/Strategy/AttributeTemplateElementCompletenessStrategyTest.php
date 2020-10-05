<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Completeness\Tests\Domain\Calculator\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Completeness\Domain\Calculator\Strategy\AttributeTemplateElementCompletenessStrategy;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\ValueObject\TemplateElement\AttributeTemplateElementProperty;
use Ergonode\Editor\Domain\Entity\ProductDraft;
use Ergonode\Product\Infrastructure\Calculator\TranslationInheritanceCalculator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class AttributeTemplateElementCompletenessStrategyTest extends TestCase
{
    /**
     * @var AttributeRepositoryInterface|MockObject
     */
    private AttributeRepositoryInterface $repository;

    /**
     * @var TranslationInheritanceCalculator|MockObject
     */
    private TranslationInheritanceCalculator $calculator;

    /**
     */
    protected function setUp(): void
    {
        $this->repository = $this->createMock(AttributeRepositoryInterface::class);
        $this->calculator = $this->createMock(TranslationInheritanceCalculator::class);
    }

    /**
     */
    public function testSupport():void
    {
        $strategy = new AttributeTemplateElementCompletenessStrategy($this->repository, $this->calculator);
        $this::assertTrue($strategy->supports(AttributeTemplateElementProperty::VARIANT));
        $this::assertFalse($strategy->supports('Any other variant'));
    }

    /**
     */
    public function testGetElementCompletenessForNotExistsAttribute(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $draft = $this->createMock(ProductDraft::class);
        $language = $this->createMock(Language::class);
        $property = $this->createMock(AttributeTemplateElementProperty::class);

        $strategy = new AttributeTemplateElementCompletenessStrategy($this->repository, $this->calculator);
        $strategy->getElementCompleteness($draft, $language, $property);
    }

    /**
     */
    public function testGetElementCompleteness(): void
    {
        $attribute = $this->createMock(AbstractAttribute::class);
        $this->repository->method('load')->willReturn($attribute);
        $draft = $this->createMock(ProductDraft::class);
        $draft->expects(self::once())->method('hasAttribute')->willReturn(true);
        $language = $this->createMock(Language::class);
        $property = $this->createMock(AttributeTemplateElementProperty::class);

        $strategy = new AttributeTemplateElementCompletenessStrategy($this->repository, $this->calculator);
        $strategy->getElementCompleteness($draft, $language, $property);
    }
}
