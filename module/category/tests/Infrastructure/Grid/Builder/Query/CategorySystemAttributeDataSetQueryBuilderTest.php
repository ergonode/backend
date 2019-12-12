<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Tests\Infrastructure\Grid\Builder\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Category\Domain\Entity\Attribute\CategorySystemAttribute;
use Ergonode\Category\Infrastructure\Grid\Builder\Query\CategorySystemAttributeDataSetQueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use PHPUnit\Framework\TestCase;

/**
 */
class CategorySystemAttributeDataSetQueryBuilderTest extends TestCase
{
    /**
     */
    public function testSupportedType(): void
    {
        $builder = new CategorySystemAttributeDataSetQueryBuilder();
        $this->assertTrue($builder->supports($this->createMock(CategorySystemAttribute::class)));
    }

    /**
     */
    public function testUnSupportedType(): void
    {
        $builder = new CategorySystemAttributeDataSetQueryBuilder();
        $this->assertFalse($builder->supports($this->createMock(AbstractAttribute::class)));
    }

    /**
     */
    public function testAddSelect(): void
    {
        $query = $this->createMock(QueryBuilder::class);
        $query->expects($this->once())->method('addSelect');
        $attribute = $this->createMock(AbstractAttribute::class);
        $language = $this->createMock(Language::class);
        $key = 'Any key';

        $builder = new CategorySystemAttributeDataSetQueryBuilder();
        $builder->addSelect($query, $key, $attribute, $language);
    }
}
