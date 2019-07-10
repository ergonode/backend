<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Renderer;

use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\ColumnInterface;

/**
 */
class ColumnRenderer
{
    /**
     * @var FilterRenderer
     */
    private $filterRenderer;

    /**
     * @param FilterRenderer $filterRenderer
     */
    public function __construct(FilterRenderer $filterRenderer)
    {
        $this->filterRenderer = $filterRenderer;
    }

    /**
     * @param AbstractGrid $grid
     * @param array        $row
     *
     * @return array
     */
    public function render(AbstractGrid $grid, array $row): array
    {
        $result = [];
        foreach ($grid->getColumns() as $id => $column) {
            $result[] = $this->renderColumn($id, $column, $grid->getConfiguration());
        }

        return $result;
    }

    /**
     * @param string          $id
     * @param ColumnInterface $column
     * @param array           $configuration
     *
     * @return array
     */
    public function renderColumn(string $id, ColumnInterface $column, array $configuration): array
    {
        $result = [];
        $result['id'] = $id;
        if ($column->getLanguage()) {
            $result['id'] = sprintf('%s:%s', $column->getField(), $column->getLanguage()->getCode());
        }
        $result['type'] = $column->getType();
        $result['label'] = $column->getLabel();
        $result['visible'] = $column->isVisible();
        if (isset($configuration[AbstractGrid::PARAMETER_ALLOW_COLUMN_EDIT]) && $configuration[AbstractGrid::PARAMETER_ALLOW_COLUMN_EDIT] === true) {
            $result['editable'] = $column->isEditable();
        } else {
            $result['editable'] = false;
        }

        if ($column->getLanguage()) {
            $result['language'] = $column->getLanguage()->getCode();
        }

        if ($column->getFilter()) {
            $result['filter'] = $this->filterRenderer->render($column->getFilter());
        }

        if ($column->getWidth()) {
            $result['width'] = $column->getWidth();
        }

        foreach ($column->getExtensions() as $key => $value) {
            $result[$key] = $value;
        }

        return $result;
    }
}
