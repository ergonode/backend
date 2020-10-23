<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Product\Infrastructure\Grid\Builder\ProductGridColumnBuilder;

class ProductGrid extends AbstractGrid
{
    private ProductGridColumnBuilder $builder;

    public function __construct(ProductGridColumnBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @throws \Exception
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $columns = $this->builder->build($configuration, $language);
        foreach ($columns as $key => $column) {
            $this->addColumn($key, $column);
        }

        $this->orderBy('index', 'DESC');
    }
}
