<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Product\Infrastructure\Grid\Builder\ProductGridColumnBuilder;

/**
 */
class ProductGrid extends AbstractGrid
{
    /**
     * @var ProductGridColumnBuilder
     */
    private ProductGridColumnBuilder $builder;

    /**
     * @param ProductGridColumnBuilder $builder
     */
    public function __construct(ProductGridColumnBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     *
     * @throws \Exception
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $columns = $this->builder->build($configuration, $language);
        foreach ($columns as $key => $column) {
            $this->addColumn($key, $column);
        }

        $this->setConfiguration(self::PARAMETER_ALLOW_COLUMN_RESIZE, true);
        $this->setConfiguration(self::PARAMETER_ALLOW_COLUMN_MOVE, true);
        $this->setConfiguration(self::PARAMETER_ALLOW_COLUMN_EDIT, true);
        $this->orderBy('index', 'DESC');
    }
}
