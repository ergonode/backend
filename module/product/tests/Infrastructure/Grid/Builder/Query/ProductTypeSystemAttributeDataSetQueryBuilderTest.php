<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Infrastructure\Grid\Builder\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Infrastructure\Grid\Builder\Query\ProductTypeSystemAttributeDataSetQueryBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\Product\Domain\Entity\Attribute\ProductTypeSystemAttribute;

class ProductTypeSystemAttributeDataSetQueryBuilderTest extends TestCase
{
    /**
     * @var ProductTypeSystemAttribute|MockObject
     */
    private ProductTypeSystemAttribute $attribute;

    /**
     * @var QueryBuilder|MockObject
     */
    private QueryBuilder $queryBuilder;

    /**
     * @var Language|MockObject
     */
    private Language $language;

    protected function setUp(): void
    {
        $this->attribute = $this->createMock(ProductTypeSystemAttribute::class);
        $this->queryBuilder = $this->createMock(QueryBuilder::class);
        $this->language = $this->createMock(Language::class);
    }

    public function testIsSupported(): void
    {
        $builder = new ProductTypeSystemAttributeDataSetQueryBuilder();
        $this->assertTrue($builder->supports($this->attribute));
    }

    public function testIsNotSupported(): void
    {
        $builder = new ProductTypeSystemAttributeDataSetQueryBuilder();
        $this->assertFalse($builder->supports($this->createMock(AbstractAttribute::class)));
    }

    public function testAddQuerySelect(): void
    {
        $this->queryBuilder->expects($this->once())->method('addSelect');
        $builder = new ProductTypeSystemAttributeDataSetQueryBuilder();
        $builder->addSelect($this->queryBuilder, 'any key', $this->attribute, $this->language);
    }
}
