<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Renderer;

use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\Renderer\ColumnRendererInterface;

/**
 */
class RowRenderer implements RowRendererInterface
{
    /**
     * @var ColumnRendererInterface[]
     */
    private $rendererCollection;

    /**
     * @param ColumnRendererInterface ...$collection
     */
    public function __construct(ColumnRendererInterface ...$collection)
    {
        $this->rendererCollection = $collection;
    }

    /**
     * {@inheritDoc}
     */
    public function render(AbstractGrid $grid, array $row): array
    {
        $result = [];
        foreach ($grid->getColumns() as $id => $column) {
            $columnId = $id;
            if ($column->hasLanguage()) {
                $columnId = sprintf('%s:%s', $column->getField(), $column->getLanguage()->getCode());
            }

            // @todo Might be slow, we need to group it in my opinion
            foreach ($this->rendererCollection as $renderer) {
                if ($renderer->supports($column)) {
                    $result[$columnId] = $renderer->render($column, $id, $row);
                    break;
                }
            }
        }

        return $result;
    }
}
