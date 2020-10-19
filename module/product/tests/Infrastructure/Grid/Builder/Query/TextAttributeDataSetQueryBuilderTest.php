<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Tests\Infrastructure\Grid\Builder\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\TextAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\DateAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Infrastructure\Grid\Builder\Query\TextAttributeDataSetQueryBuilder;
use Ergonode\Product\Infrastructure\Strategy\ProductAttributeLanguageResolver;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;

/**
 */
class TextAttributeDataSetQueryBuilderTest extends TestCase
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
     * @var LanguageQueryInterface|MockObject
     */
    private LanguageQueryInterface $query;

    /**
     * @var ProductAttributeLanguageResolver
     */
    private ProductAttributeLanguageResolver $resolver;

    /**
     */
    protected function setUp(): void
    {
        $this->attribute = $this->createMock(TextAttribute::class);
        $this->queryBuilder = $this->createMock(QueryBuilder::class);
        $this->language = $this->createMock(Language::class);
        $this->query = $this->createMock(LanguageQueryInterface::class);
        $this->query->method('getLanguageNodeInfo')->willReturn(['lft' => 1, 'rgt' => 10]);
        $this->resolver = new ProductAttributeLanguageResolver($this->query);
    }

    /**
     */
    public function testIsSupported(): void
    {
        $builder = new TextAttributeDataSetQueryBuilder($this->query, $this->resolver);
        self::assertTrue($builder->supports($this->attribute));
    }

    /**
     */
    public function testIsNotSupported(): void
    {
        $builder = new TextAttributeDataSetQueryBuilder($this->query, $this->resolver);
        self::assertFalse($builder->supports($this->createMock(AbstractAttribute::class)));
    }

    /**
     */
    public function testAddQuerySelect(): void
    {
        $this->queryBuilder->expects(self::once())->method('addSelect');
        $builder = new TextAttributeDataSetQueryBuilder($this->query, $this->resolver);
        $builder->addSelect($this->queryBuilder, 'any key', $this->attribute, $this->language);
    }
}
