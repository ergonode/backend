<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Tests\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\NumericAttribute;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\Range;
use Ergonode\Grid\Column\NumericColumn;
use Ergonode\Grid\Filter\RangeFilter;
use Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy\NumericAttributeColumnStrategy;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class NumericAttributeColumnStrategyTest extends TestCase
{
    /**
     * @var AttributeQueryInterface|MockObject
     */
    private $query;

    /**
     * @var NumericAttribute|MockObject
     */
    private $attribute;

    /**
     */
    protected function setUp(): void
    {
        $this->query = $this->createMock(AttributeQueryInterface::class);
        $this->attribute = $this->createMock(NumericAttribute::class);
        $this->query->method('getAttributeValueRange')->willReturn(new Range(0, 100));
        $this->attribute->method('getId')->willReturn($this->createMock(AttributeId::class));
    }

    /**
     */
    public function testIsSupported(): void
    {
        $strategy = new NumericAttributeColumnStrategy($this->query);
        $this->assertTrue($strategy->supports($this->attribute));
    }

    /**
     */
    public function testIsNotSupported(): void
    {
        $strategy = new NumericAttributeColumnStrategy($this->query);
        $this->assertFalse($strategy->supports($this->createMock(AbstractAttribute::class)));
    }

    /**
     */
    public function testCreateColumn(): void
    {
        $language = $this->createMock(Language::class);
        $strategy = new NumericAttributeColumnStrategy($this->query);
        $column = $strategy->create($this->attribute, $language);
        $this->assertInstanceOf(NumericColumn::class, $column);
        $this->assertInstanceOf(RangeFilter::class, $column->getFilter());
    }
}
