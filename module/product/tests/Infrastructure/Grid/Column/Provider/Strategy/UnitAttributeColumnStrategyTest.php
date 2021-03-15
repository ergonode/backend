<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\UnitAttribute;
use Ergonode\Core\Domain\Entity\Unit;
use Ergonode\Core\Domain\Repository\UnitRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\NumericColumn;
use Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy\UnitAttributeColumnStrategy;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\Grid\Filter\NumericFilter;

class UnitAttributeColumnStrategyTest extends TestCase
{
    /**
     * @var UnitRepositoryInterface | MockObject
     */
    private $unitRepository;

    /**
     * @var UnitAttribute|MockObject
     */
    private $attribute;

    protected function setUp(): void
    {
        $this->unitRepository = $this->createMock(UnitRepositoryInterface::class);
        $this->attribute = $this->createMock(UnitAttribute::class);
        $this->unitRepository->method('load')->willReturn($this->createMock(Unit::class));
        $this->attribute->method('getId')->willReturn($this->createMock(AttributeId::class));
    }

    public function testIsSupported(): void
    {
        $strategy = new UnitAttributeColumnStrategy($this->unitRepository);
        $this->assertTrue($strategy->supports($this->attribute));
    }

    public function testIsNotSupported(): void
    {
        $strategy = new UnitAttributeColumnStrategy($this->unitRepository);
        $this->assertFalse($strategy->supports($this->createMock(AbstractAttribute::class)));
    }

    public function testCreateColumn(): void
    {
        $language = $this->createMock(Language::class);
        $strategy = new UnitAttributeColumnStrategy($this->unitRepository);
        $column = $strategy->create($this->attribute, $language);
        $this->assertInstanceOf(NumericColumn::class, $column);
        $this->assertInstanceOf(NumericFilter::class, $column->getFilter());
    }
}
