<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Grid\Column\DateTimeColumn;
use Ergonode\Grid\Filter\DateTimeFilter;
use Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy\EditedAtSystemAttributeColumnBuilderStrategy;
use PHPUnit\Framework\TestCase;
use Ergonode\Product\Domain\Entity\Attribute\EditedAtSystemAttribute;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;

class EditedAtSystemAttributeColumnBuilderStrategyTest extends TestCase
{
    /**
     * @var EditedAtSystemAttribute|MockObject
     */
    private $attribute;

    protected function setUp(): void
    {
        $this->attribute = $this->createMock(EditedAtSystemAttribute::class);
        $this->attribute->method('getId')->willReturn($this->createMock(AttributeId::class));
    }

    public function testIsSupported(): void
    {
        $strategy = new EditedAtSystemAttributeColumnBuilderStrategy();
        self::assertTrue($strategy->supports($this->attribute));
    }

    public function testIsNotSupported(): void
    {
        $strategy = new EditedAtSystemAttributeColumnBuilderStrategy();
        self::assertFalse($strategy->supports($this->createMock(AbstractAttribute::class)));
    }

    public function testCreateColumn(): void
    {
        $language = $this->createMock(Language::class);
        $strategy = new EditedAtSystemAttributeColumnBuilderStrategy();
        $column = $strategy->create($this->attribute, $language);
        self::assertInstanceOf(DateTimeColumn::class, $column);
        self::assertInstanceOf(DateTimeFilter::class, $column->getFilter());
    }
}
