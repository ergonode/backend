<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Tests\Infrastructure\Grid\Builder\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\DateAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Infrastructure\Grid\Builder\Query\MultiSelectAttributeDataSetQueryBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class MultiSelectAttributeDataSetQueryBuilderTest extends TestCase
{
    /**
     * @var DateAttribute|MockObject
     */
    private $attribute;

    /**
     * @var QueryBuilder|MockObject
     */
    private $queryBuilder;

    /**
     * @var Language|MockObject
     */
    private $language;

    /**
     */
    protected function setUp(): void
    {
        $this->attribute = $this->createMock(MultiSelectAttribute::class);
        $this->queryBuilder = $this->createMock(QueryBuilder::class);
        $this->language = $this->createMock(Language::class);
    }

    /**
     */
    public function testIsSupported(): void
    {
        $builder = new MultiSelectAttributeDataSetQueryBuilder();
        $this->assertTrue($builder->supports($this->attribute));
    }

    /**
     */
    public function testIsNotSupported(): void
    {
        $builder = new MultiSelectAttributeDataSetQueryBuilder();
        $this->assertFalse($builder->supports($this->createMock(AbstractAttribute::class)));
    }

    /**
     */
    public function testAddQuerySelect(): void
    {
        $this->queryBuilder->expects($this->once())->method('addSelect');
        $builder = new MultiSelectAttributeDataSetQueryBuilder();
        $builder->addSelect($this->queryBuilder, 'any key', $this->attribute, $this->language);
    }
}
