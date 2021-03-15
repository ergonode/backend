<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Infrastructure\Grid\Builder;

use Ergonode\Product\Infrastructure\Grid\Builder\DataSetQueryBuilderProvider;
use PHPUnit\Framework\TestCase;
use Ergonode\Product\Infrastructure\Grid\Builder\Query\AttributeDataSetQueryBuilderInterface;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use PHPUnit\Framework\MockObject\MockObject;

class DataSetQueryBuilderProviderTest extends TestCase
{
    /**
     * @var AbstractAttribute|MockObject
     */
    private AbstractAttribute $attribute;

    protected function setUp(): void
    {
        $this->attribute = $this->createMock(AbstractAttribute::class);
        parent::setUp();
    }

    public function testValidStrategy(): void
    {
        $strategy = $this->createMock(AttributeDataSetQueryBuilderInterface::class);
        $strategy->method('supports')->willReturn(true);
        $provider = new DataSetQueryBuilderProvider(...[$strategy]);
        $result = $provider->provide($this->attribute);
        $this->assertEquals($strategy, $result);
    }

    public function testNoValidStrategy(): void
    {
        $this->expectException(\RuntimeException::class);
        $strategy = $this->createMock(AttributeDataSetQueryBuilderInterface::class);
        $strategy->method('supports')->willReturn(false);
        $provider = new DataSetQueryBuilderProvider(...[$strategy]);
        $provider->provide($this->attribute);
    }
}
