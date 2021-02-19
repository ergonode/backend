<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Infrastructure\Filter\BatchAction;

use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilter;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Ergonode\Product\Infrastructure\Provider\FilteredQueryBuilderProvider;
use Ergonode\Product\Infrastructure\Filter\BatchAction\ProductBatchActionFilter;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductBatchActionFilterTest extends TestCase
{
    /**
     * @var FilteredQueryBuilderProvider|MockObject
     */
    private FilteredQueryBuilderProvider $filteredQueryBuilder;

    private QueryBuilder $queryBuilder;

    private Statement $statement;

    protected function setUp(): void
    {
        $this->filteredQueryBuilder = $this->createMock(FilteredQueryBuilderProvider::class);
        $this->queryBuilder = $this->createMock(QueryBuilder::class);
        $this->statement = $this->createMock(Statement::class);
        $this->filteredQueryBuilder->method('provide')->willReturn($this->queryBuilder);
        $this->queryBuilder->method('execute')->willReturn($this->statement);
    }

    public function testSupported(): void
    {
        $filter = new ProductBatchActionFilter(
            $this->filteredQueryBuilder
        );

        $type = new BatchActionType('product_delete');

        self::assertTrue($filter->supports($type));
    }

    public function testUnSupported(): void
    {
        $filter = new ProductBatchActionFilter(
            $this->filteredQueryBuilder
        );

        $type = new BatchActionType('type');

        self::assertFalse($filter->supports($type));
    }

    public function testFilterEmptyResult(): void
    {

        $this->statement->method('fetchAll')->willReturn(false);
        $this->filteredQueryBuilder->method('provide')->willReturn($this->queryBuilder);

        $batchActionFilter = $this->createMock(BatchActionFilter::class);

        $filter = new ProductBatchActionFilter(
            $this->filteredQueryBuilder
        );
        self::assertSame($filter->filter($batchActionFilter), []);
    }

    public function testFilterNotEmptyResult(): void
    {
        $this->statement->method('fetchAll')->willReturn([
            'b732920e-ae16-4b3b-8b75-557afd501c5e',
            'a1d8faeb-023f-4cb7-aa60-c7abdd252ffc',
        ]);
        $this->filteredQueryBuilder->method('provide')->willReturn($this->queryBuilder);

        $batchActionFilter = $this->createMock(BatchActionFilter::class);

        $filter = new ProductBatchActionFilter(
            $this->filteredQueryBuilder
        );
        self::assertIsArray($filter->filter($batchActionFilter));
        self::assertContainsOnlyInstancesOf(ProductId::class, $filter->filter($batchActionFilter));
    }
}
