<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Grid\Column\DateTimeColumn;
use Ergonode\Grid\Filter\DateTimeFilter;
use Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy\CreatedAtSystemAttributeColumnBuilderStrategy;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Domain\Entity\Attribute\CreatedAtSystemAttribute;

class CreatedAtSystemAttributeColumnBuilderStrategyTest extends TestCase
{
    /**
     * @var CreatedAtSystemAttribute|MockObject
     */
    private $attribute;

    protected function setUp(): void
    {
        $this->attribute = $this->createMock(CreatedAtSystemAttribute::class);
        $this->attribute->method('getId')->willReturn($this->createMock(AttributeId::class));
    }

    public function testIsSupported(): void
    {
        $strategy = new CreatedAtSystemAttributeColumnBuilderStrategy();
        self::assertTrue($strategy->supports($this->attribute));
    }

    public function testIsNotSupported(): void
    {
        $strategy = new CreatedAtSystemAttributeColumnBuilderStrategy();
        self::assertFalse($strategy->supports($this->createMock(AbstractAttribute::class)));
    }

    public function testCreateColumn(): void
    {
        $language = $this->createMock(Language::class);
        $strategy = new CreatedAtSystemAttributeColumnBuilderStrategy();
        $column = $strategy->create($this->attribute, $language);
        self::assertInstanceOf(DateTimeColumn::class, $column);
        self::assertInstanceOf(DateTimeFilter::class, $column->getFilter());
    }
}
