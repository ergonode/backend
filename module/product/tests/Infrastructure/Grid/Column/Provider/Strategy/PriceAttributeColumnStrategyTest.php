<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\NumericColumn;
use Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy\PriceAttributeColumnStrategy;
use Money\Currency;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\Grid\Filter\NumericFilter;

class PriceAttributeColumnStrategyTest extends TestCase
{
    /**
     * @var PriceAttribute|MockObject
     */
    private $attribute;

    protected function setUp(): void
    {
        $this->attribute = $this->createMock(PriceAttribute::class);
        $this->attribute->method('getId')->willReturn($this->createMock(AttributeId::class));
        $this->attribute->method('getCurrency')->willReturn(new Currency('PLN'));
    }

    public function testIsSupported(): void
    {
        $strategy = new PriceAttributeColumnStrategy();
        $this->assertTrue($strategy->supports($this->attribute));
    }

    public function testIsNotSupported(): void
    {
        $strategy = new PriceAttributeColumnStrategy();
        $this->assertFalse($strategy->supports($this->createMock(AbstractAttribute::class)));
    }

    public function testCreateColumn(): void
    {
        $language = $this->createMock(Language::class);
        $strategy = new PriceAttributeColumnStrategy();
        $column = $strategy->create($this->attribute, $language);
        $this->assertInstanceOf(NumericColumn::class, $column);
        $this->assertInstanceOf(NumericFilter::class, $column->getFilter());
    }
}
