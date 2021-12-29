<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy\ProductRelationAttributeColumnStrategy;
use PHPUnit\Framework\TestCase;
use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Domain\Entity\Attribute\ProductRelationAttribute;
use Ergonode\Product\Infrastructure\Grid\Column\ProductRelationColumn;

class ProductRelationAttributeColumnStrategyTest extends TestCase
{
    /**
     * @var PriceAttribute|MockObject
     */
    private $attribute;

    protected function setUp(): void
    {
        $this->attribute = $this->createMock(ProductRelationAttribute::class);
        $this->attribute->method('getId')->willReturn($this->createMock(AttributeId::class));
    }

    public function testIsSupported(): void
    {
        $strategy = new ProductRelationAttributeColumnStrategy();
        self::assertTrue($strategy->supports($this->attribute));
    }

    public function testIsNotSupported(): void
    {
        $strategy = new ProductRelationAttributeColumnStrategy();
        self::assertFalse($strategy->supports($this->createMock(AbstractAttribute::class)));
    }

    public function testCreateColumn(): void
    {
        $language = $this->createMock(Language::class);
        $strategy = new ProductRelationAttributeColumnStrategy();
        $column = $strategy->create($this->attribute, $language);
        self::assertInstanceOf(ProductRelationColumn::class, $column);
    }
}
