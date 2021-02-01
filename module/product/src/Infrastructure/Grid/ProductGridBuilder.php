<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Product\Infrastructure\Grid\Builder\ProductGridColumnBuilder;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;

class ProductGridBuilder implements GridBuilderInterface
{
    private ProductGridColumnBuilder $builder;

    public function __construct(ProductGridColumnBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @throws \Exception
     */
    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $grid = new ProductGrid();
        $columns = $this->builder->build($configuration, $language);
        foreach ($columns as $key => $column) {
            $grid->addColumn($key, $column);
        }

        $grid->orderBy('index', 'DESC');

        return $grid;
    }
}
