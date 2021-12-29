<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Provider;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilterInterface;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Product\Infrastructure\Factory\DataSet\DbalQueryBuilderProductDataSetFactory;
use Ergonode\Product\Infrastructure\Grid\ProductGridBuilder;
use Ergonode\BatchAction\Infrastructure\Grid\BatchActionFilterGridConfiguration;

class FilteredQueryBuilder
{
    private DbalQueryBuilderProductDataSetFactory $productQueryBuilderFactory;

    private ProductGridBuilder $gridBuilder;

    private ProductIdsProvider $productIdsProvider;

    private LanguageQueryInterface $languageQuery;

    public function __construct(
        DbalQueryBuilderProductDataSetFactory $productQueryBuilderFactory,
        ProductGridBuilder $gridBuilder,
        ProductIdsProvider $productIdsProvider,
        LanguageQueryInterface $languageQuery
    ) {
        $this->productQueryBuilderFactory = $productQueryBuilderFactory;
        $this->gridBuilder = $gridBuilder;
        $this->productIdsProvider = $productIdsProvider;
        $this->languageQuery = $languageQuery;
    }

    public function build(BatchActionFilterInterface $filter): QueryBuilder
    {
        $language = $this->languageQuery->getRootLanguage();
        $configuration = new BatchActionFilterGridConfiguration($filter);

        $grid = $this->gridBuilder->build($configuration, $language);

        return $this->productIdsProvider->getProductIds(
            $grid,
            $configuration,
            $this->productQueryBuilderFactory->create()
        );
    }
}
