<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\NumericAttribute;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\NumericColumn;
use Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy\NumericAttributeColumnStrategy;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\Grid\Filter\NumericFilter;

class NumericAttributeColumnStrategyTest extends TestCase
{
    /**
     * @var NumericAttribute|MockObject
     */
    private $attribute;

    protected function setUp(): void
    {
        $this->attribute = $this->createMock(NumericAttribute::class);
        $this->attribute->method('getId')->willReturn($this->createMock(AttributeId::class));
    }

    public function testIsSupported(): void
    {
        $strategy = new NumericAttributeColumnStrategy();
        $this->assertTrue($strategy->supports($this->attribute));
    }

    public function testIsNotSupported(): void
    {
        $strategy = new NumericAttributeColumnStrategy();
        $this->assertFalse($strategy->supports($this->createMock(AbstractAttribute::class)));
    }

    public function testCreateColumn(): void
    {
        $language = $this->createMock(Language::class);
        $strategy = new NumericAttributeColumnStrategy();
        $column = $strategy->create($this->attribute, $language);
        $this->assertInstanceOf(NumericColumn::class, $column);
        $this->assertInstanceOf(NumericFilter::class, $column->getFilter());
    }
}
