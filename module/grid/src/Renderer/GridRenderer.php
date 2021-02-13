<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Renderer;

use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\GridInterface;

class GridRenderer
{
    private ColumnRenderer $columnRenderer;

    private RowRendererInterface $rowRenderer;

    private ActionRenderer $actionRenderer;

    private InfoRender $infoRenderer;

    public function __construct(
        ColumnRenderer $columnRenderer,
        RowRendererInterface $rowRenderer,
        ActionRenderer $actionRenderer,
        InfoRender $infoRenderer
    ) {
        $this->columnRenderer = $columnRenderer;
        $this->rowRenderer = $rowRenderer;
        $this->actionRenderer = $actionRenderer;
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

        foreach ($records as $record) {
            $row = $this->rowRenderer->render($grid, $configuration, $record);
            $row['_links']['value'] = $this->actionRenderer->render($grid, $record);
            $result['collection'][] = $row;
        }

        $result['info'] = $this->infoRenderer->render($grid, $configuration, $dataSet);

        return $result;
    }
}
