<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Infrastructure\Filter\BatchAction;

use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilter;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionIds;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Infrastructure\Factory\DataSet\DbalProductDataSetFactory;
use Ergonode\Product\Infrastructure\Filter\BatchAction\ProductBatchActionFilter;
use Ergonode\Product\Infrastructure\Grid\ProductGrid;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\AggregateId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductBatchActionFilterTest extends TestCase
{
    /**
     * @var ProductQueryInterface|MockObject
     */
    private ProductQueryInterface $productQuery;

    /**
     * @var DbalProductDataSetFactory|MockObject
     */
    private DbalProductDataSetFactory $dataSetFactory;

    /**
     * @var ProductGrid|MockObject
     */
    private ProductGrid $productGrid;

    /**
     * @var GridRenderer|MockObject
     */
    private GridRenderer $gridRenderer;

    protected function setUp(): void
    {
        $this->productQuery = $this->createMock(ProductQueryInterface::class);
        $this->dataSetFactory = $this->createMock(DbalProductDataSetFactory::class);
        $this->productGrid = $this->createMock(ProductGrid::class);
        $this->gridRenderer = $this->createMock(GridRenderer::class);
    }

    public function testSupported(): void
    {
        $filter = new ProductBatchActionFilter(
            $this->productQuery,
            $this->dataSetFactory,
            $this->productGrid,
            $this->gridRenderer
        );

        $type = new BatchActionType('product_delete');

        self::assertTrue($filter->supports($type));
    }

    public function testUnSupported(): void
    {
        $filter = new ProductBatchActionFilter(
            $this->productQuery,
            $this->dataSetFactory,
            $this->productGrid,
            $this->gridRenderer
        );

        $type = new BatchActionType('type');

        self::assertFalse($filter->supports($type));
    }

    public function testFilterAll(): void
    {
        $productId = ProductId::generate();
        $this->productQuery->method('getAllIds')->willReturn([$productId->getValue()]);

        $filter = new ProductBatchActionFilter(
            $this->productQuery,
            $this->dataSetFactory,
            $this->productGrid,
            $this->gridRenderer
        );

        self::assertIsArray($filter->filter(null));
        self::assertContainsOnlyInstancesOf(ProductId::class, $filter->filter(null));
    }

    public function testFilterAllEmptyFilter(): void
    {
        $this->productQuery->method('getAllIds')->willReturn([ProductId::generate()->getValue()]);

        $batchActionFilter = $this->createMock(BatchActionFilter::class);

        $filter = new ProductBatchActionFilter(
            $this->productQuery,
            $this->dataSetFactory,
            $this->productGrid,
            $this->gridRenderer
        );

        self::assertIsArray($filter->filter($batchActionFilter));
        self::assertContainsOnlyInstancesOf(ProductId::class, $filter->filter($batchActionFilter));
    }

    public function testFilterIncludeIdsFilter(): void
    {
        $batchActionIds = $this->createMock(BatchActionIds::class);
        $batchActionIds->method('getList')->willReturn([AggregateId::generate()]);
        $batchActionIds->method('isIncluded')->willReturn(true);

        $batchActionFilter = $this->createMock(BatchActionFilter::class);

        $batchActionFilter->method('getIds')->willReturn($batchActionIds);

        $filter = new ProductBatchActionFilter(
            $this->productQuery,
            $this->dataSetFactory,
            $this->productGrid,
            $this->gridRenderer
        );

        self::assertIsArray($filter->filter($batchActionFilter));
        self::assertContainsOnlyInstancesOf(AggregateId::class, $filter->filter($batchActionFilter));
    }

    public function testFilterExcludeIdsFilter(): void
    {
        $this->productQuery->method('getAllIds')->willReturn([ProductId::generate()->getValue()]);

        $batchActionIds = $this->createMock(BatchActionIds::class);
        $batchActionIds->method('getList')->willReturn([AggregateId::generate()]);
        $batchActionIds->method('isIncluded')->willReturn(false);

        $batchActionFilter = $this->createMock(BatchActionFilter::class);

        $batchActionFilter->method('getIds')->willReturn($batchActionIds);


        $filter = new ProductBatchActionFilter(
            $this->productQuery,
            $this->dataSetFactory,
            $this->productGrid,
            $this->gridRenderer
        );

        self::assertIsArray($filter->filter($batchActionFilter));
        self::assertContainsOnlyInstancesOf(ProductId::class, $filter->filter($batchActionFilter));
    }

    public function testFilterByQuery(): void
    {
        $this->gridRenderer->method('render')->willReturn(
            [
                'collection' => [],
            ]
        );

        $batchActionFilter = $this->createMock(BatchActionFilter::class);
        $batchActionFilter
            ->method('getQuery')
            ->willReturn('code_41:en_GB=f5e5e0ba-cb12-4365-88a1-ea21d040c2cc');

        $filter = new ProductBatchActionFilter(
            $this->productQuery,
            $this->dataSetFactory,
            $this->productGrid,
            $this->gridRenderer
        );

        self::assertIsArray($filter->filter($batchActionFilter));
        self::assertContainsOnlyInstancesOf(ProductId::class, $filter->filter($batchActionFilter));
    }


    public function testFilterByQueryAndIncludeIds(): void
    {
        $this->gridRenderer->method('render')->willReturn(
            [
                'collection' => [],
            ]
        );

        $batchActionIds = $this->createMock(BatchActionIds::class);
        $batchActionIds->method('getList')->willReturn([AggregateId::generate()]);
        $batchActionIds->method('isIncluded')->willReturn(true);

        $batchActionFilter = $this->createMock(BatchActionFilter::class);
        $batchActionFilter->method('getQuery')
            ->willReturn('code_41:en_GB=f5e5e0ba-cb12-4365-88a1-ea21d040c2cc');
        $batchActionFilter->method('getIds')->willReturn($batchActionIds);

        $filter = new ProductBatchActionFilter(
            $this->productQuery,
            $this->dataSetFactory,
            $this->productGrid,
            $this->gridRenderer
        );

        self::assertIsArray($filter->filter($batchActionFilter));
        self::assertContainsOnlyInstancesOf(AggregateId::class, $filter->filter($batchActionFilter));
    }
}
