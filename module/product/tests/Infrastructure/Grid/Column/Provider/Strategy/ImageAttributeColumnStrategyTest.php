<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Tests\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\ImageAttribute;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\ImageColumn;
use Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy\ImageAttributeColumnStrategy;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class ImageAttributeColumnStrategyTest extends TestCase
{
    /**
     * @var ImageAttribute|MockObject
     */
    private $attribute;

    /**
     */
    protected function setUp(): void
    {
        $this->attribute = $this->createMock(ImageAttribute::class);
        $this->attribute->method('getId')->willReturn($this->createMock(AttributeId::class));
    }

    /**
     */
    public function testIsSupported(): void
    {
        $strategy = new ImageAttributeColumnStrategy();
        $this->assertTrue($strategy->supports($this->attribute));
    }

    /**
     */
    public function testIsNotSupported(): void
    {
        $strategy = new ImageAttributeColumnStrategy();
        $this->assertFalse($strategy->supports($this->createMock(AbstractAttribute::class)));
    }

    /**
     */
    public function testCreateColumn(): void
    {
        $language = $this->createMock(Language::class);
        $strategy = new ImageAttributeColumnStrategy();
        $column = $strategy->create($this->attribute, $language);
        $this->assertInstanceOf(ImageColumn::class, $column);
        $this->assertNull($column->getFilter());
    }
}
