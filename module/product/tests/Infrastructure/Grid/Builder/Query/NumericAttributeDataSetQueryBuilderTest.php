<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Infrastructure\Grid\Builder\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\NumericAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Infrastructure\Grid\Builder\Query\NumericAttributeDataSetQueryBuilder;
use Ergonode\Product\Infrastructure\Strategy\ProductAttributeLanguageResolver;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;

class NumericAttributeDataSetQueryBuilderTest extends TestCase
{
    /**
     * @var NumericAttribute|MockObject
     */
    private NumericAttribute $attribute;

    /**
     * @var QueryBuilder|MockObject
     */
    private QueryBuilder $queryBuilder;

    /**
     * @var Language|MockObject
     */
    private Language $language;

    /**
     * @var LanguageQueryInterface|MockObject
     */
    private LanguageQueryInterface $query;

    private ProductAttributeLanguageResolver $resolver;

    protected function setUp(): void
    {
        $this->attribute = $this->createMock(NumericAttribute::class);
        $this->queryBuilder = $this->createMock(QueryBuilder::class);
        $this->language = $this->createMock(Language::class);
        $this->query = $this->createMock(LanguageQueryInterface::class);
        $this->query->method('getLanguageNodeInfo')->willReturn(['lft' => 1, 'rgt' => 10]);
        $this->resolver = new ProductAttributeLanguageResolver($this->query);
    }

    public function testIsSupported(): void
    {
        $builder = new NumericAttributeDataSetQueryBuilder($this->query, $this->resolver);
        $this->assertTrue($builder->supports($this->attribute));
    }

    public function testIsNotSupported(): void
    {
        $builder = new NumericAttributeDataSetQueryBuilder($this->query, $this->resolver);
        $this->assertFalse($builder->supports($this->createMock(AbstractAttribute::class)));
    }

    public function testAddQuerySelect(): void
    {
        $this->queryBuilder->expects($this->once())->method('addSelect');
        $builder = new NumericAttributeDataSetQueryBuilder($this->query, $this->resolver);
        $builder->addSelect($this->queryBuilder, 'any key', $this->attribute, $this->language);
    }
}
