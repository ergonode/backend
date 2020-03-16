<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Renderer;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Product\Infrastructure\Grid\ProductGrid;

/**
 */
class GridRenderer
{
    /**
     * @var ColumnRenderer
     */
    private ColumnRenderer $columnRenderer;

    /**
     * @var RowRendererInterface
     */
    private RowRendererInterface $rowRenderer;

    /**
     * @var InfoRender
     */
    private InfoRender $infoRenderer;

    /**
     * @param ColumnRenderer       $columnRenderer
     * @param RowRendererInterface $rowRenderer
     * @param InfoRender           $infoRenderer
     */
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
     * @param AbstractGrid               $grid
     * @param GridConfigurationInterface $configuration
     * @param DataSetInterface           $dataSet
     * @param Language                   $language
     *
     * @return array
     */
    public function render(
        AbstractGrid $grid,
        GridConfigurationInterface $configuration,
        DataSetInterface $dataSet,
        Language $language
    ): array {
        $grid->init($configuration, $language);

        $field = $configuration->getField() ?: $grid->getField();
        $order = $configuration->getOrder() ?: $grid->getOrder();
        $records = $dataSet->getItems(
            $grid->getColumns(),
            $configuration->getFilters(),
            $configuration->getLimit(),
            $configuration->getOffset(),
            $field,
            $order
        );

        if (GridConfigurationInterface::VIEW_GRID === $configuration->getView()) {
            $result['configuration'] = $grid->getConfiguration();
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
