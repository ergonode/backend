<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Renderer;

use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\GridInterface;
use Ergonode\Product\Infrastructure\Grid\ProductGrid;

class GridRenderer
{
    private ColumnRenderer $columnRenderer;

    private RowRendererInterface $rowRenderer;

    private InfoRender $infoRenderer;

    public function __construct(
        ColumnRenderer $columnRenderer,
        RowRendererInterface $rowRenderer,
        InfoRender $infoRenderer
    ) {
        $this->columnRenderer = $columnRenderer;
        $this->rowRenderer = $rowRenderer;
        $this->infoRenderer = $infoRenderer;
    }

    /**
     * @return array
     */
    public function render(
        GridInterface $grid,
        GridConfigurationInterface $configuration,
        DataSetInterface $dataSet
    ): array {
        $field = $grid->getField();
        $order = $grid->getOrder();
        if ($configuration->getField()) {
            $field = $configuration->getField();
            $order = $configuration->getOrder();
        }

        $records = $dataSet->getItems(
            $grid->getColumns(),
            $configuration->getFilters(),
            $configuration->getLimit(),
            $configuration->getOffset(),
            $field,
            $order
        );

        if (GridConfigurationInterface::VIEW_GRID === $configuration->getView()) {
            $result['columns'] = $this->columnRenderer->render($grid, $configuration);

            // todo temporary hax - waiting for frontend changes
            if ($grid instanceof ProductGrid && !empty($configuration->getColumns())) {
                $columnsOrdered = [];
                foreach (array_keys($configuration->getColumns()) as $name) {
                    foreach ($result['columns'] as $key => $column) {
                        if ($name === $column['id']) {
                            $columnsOrdered[] = $result['columns'][$key];
                            break;
                        }
                    }
                }

                $result['columns'] = $columnsOrdered;
            }
        }
        $result['collection'] = [];

        foreach ($records as $row) {
            $result['collection'][] = $this->rowRenderer->render($grid, $configuration, $row);
        }

        $result['info'] = $this->infoRenderer->render($grid, $configuration, $dataSet);

        return $result;
    }
}
