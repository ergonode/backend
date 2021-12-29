<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Infrastructure\Grid;

use Ergonode\BatchAction\Infrastructure\Grid\BatchActionFilterGridConfiguration;
use PHPUnit\Framework\TestCase;
use Ergonode\Grid\Request\RequestColumn;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionIds;
use Ergonode\Grid\Request\FilterValue;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilterInterface;

class BatchActionFilterGridConfigurationTest extends TestCase
{
    private BatchActionFilterInterface $batchActionFilter;

    protected function setUp(): void
    {
        $this->batchActionFilter = $this->createMock(BatchActionFilterInterface::class);
    }

    public function testBasicConstValues(): void
    {
        $configuration = new BatchActionFilterGridConfiguration($this->batchActionFilter);

        self::assertSame(BatchActionFilterGridConfiguration::VIEW_LIST, $configuration->getView());
        self::assertSame(BatchActionFilterGridConfiguration::ASC, $configuration->getOrder());
        self::assertSame(BatchActionFilterGridConfiguration::LIMIT, $configuration->getLimit());
        self::assertSame(BatchActionFilterGridConfiguration::OFFSET, $configuration->getOffset());
        self::assertFalse($configuration->isExtended());
        self::assertNull($configuration->getField());
        self::assertEmpty($configuration->getColumns());
    }

    public function testWithQuery(): void
    {
        $this->batchActionFilter->method('getQuery')->willReturn('field=value');
        $configuration = new BatchActionFilterGridConfiguration($this->batchActionFilter);
        $columns = $configuration->getColumns();
        $filters = $configuration->getFilters();

        self::assertCount(1, $columns);
        self::assertCount(1, $filters);

        /** @var RequestColumn $column */
        $column = reset($columns);

        self::assertNull($column->getLanguage());
        self::assertSame('field', $column->getKey());
        self::assertSame('field', $column->getColumn());
        self::assertFalse($column->isShow());

        $filters = reset($filters);
        /** @phpstan-ignore-next-line */
        self::assertArrayHasKey('field', $filters);
        /** @phpstan-ignore-next-line */
        $filters = reset($filters);
        /** @phpstan-ignore-next-line */
        self::assertCount(1, $filters);
        /** @var FilterValue $filter */
        /** @phpstan-ignore-next-line */
        $filter = reset($filters);

        self::assertNull($filter->getLanguage());
        self::assertSame('field', $filter->getColumn());
        self::assertSame('value', $filter->getValue());
        self::assertSame('=', $filter->getOperator());
    }

    public function testWithIncludeIds(): void
    {
        $id = AggregateId::generate();
        $ids = $this->createMock(BatchActionIds::class);
        $ids->method('isIncluded')->willReturn(true);
        $ids->method('getList')->willReturn([$id]);

        $this->batchActionFilter->method('getIds')->willReturn($ids);
        $configuration = new BatchActionFilterGridConfiguration($this->batchActionFilter);
        $columns = $configuration->getColumns();
        $filters = $configuration->getFilters();

        self::assertCount(1, $columns);
        self::assertCount(1, $filters);

        /** @var RequestColumn $column */
        $column = reset($columns);

        self::assertNull($column->getLanguage());
        self::assertSame('id', $column->getKey());
        self::assertSame('id', $column->getColumn());
        self::assertFalse($column->isShow());

        $filters = reset($filters);
        /** @phpstan-ignore-next-line */
        self::assertArrayHasKey('id', $filters);
        /** @phpstan-ignore-next-line */
        $filters = reset($filters);
        /** @phpstan-ignore-next-line */
        self::assertCount(1, $filters);
        /** @var FilterValue $filter */
        /** @phpstan-ignore-next-line */
        $filter = reset($filters);

        self::assertNull($filter->getLanguage());
        self::assertSame('id', $filter->getColumn());
        self::assertSame($id->getValue(), $filter->getValue());
        self::assertSame('=', $filter->getOperator());
    }

    public function testWithExcludeIds(): void
    {
        $id = AggregateId::generate();
        $ids = $this->createMock(BatchActionIds::class);
        $ids->method('isIncluded')->willReturn(false);
        $ids->method('getList')->willReturn([$id]);

        $this->batchActionFilter->method('getIds')->willReturn($ids);
        $configuration = new BatchActionFilterGridConfiguration($this->batchActionFilter);
        $columns = $configuration->getColumns();
        $filters = $configuration->getFilters();

        self::assertCount(1, $columns);
        self::assertCount(1, $filters);

        /** @var RequestColumn $column */
        $column = reset($columns);

        self::assertNull($column->getLanguage());
        self::assertSame('id', $column->getKey());
        self::assertSame('id', $column->getColumn());
        self::assertFalse($column->isShow());

        $filters = reset($filters);
        /** @phpstan-ignore-next-line */
        self::assertArrayHasKey('id', $filters);
        /** @phpstan-ignore-next-line */
        $filters = reset($filters);
        /** @phpstan-ignore-next-line */
        self::assertCount(1, $filters);
        /** @var FilterValue $filter */
        /** @phpstan-ignore-next-line */
        $filter = reset($filters);

        self::assertNull($filter->getLanguage());
        self::assertSame('id', $filter->getColumn());
        self::assertSame($id->getValue(), $filter->getValue());
        self::assertSame('!=', $filter->getOperator());
    }
}
