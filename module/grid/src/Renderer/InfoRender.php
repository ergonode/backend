<?php


namespace Ergonode\Grid\Renderer;

use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\GridConfigurationInterface;

/**
 */
class InfoRender
{
    /**
     * @param AbstractGrid               $grid
     * @param GridConfigurationInterface $configuration
     * @param DataSetInterface           $dataSet
     *
     * @return array
     */
    public function render(AbstractGrid $grid, GridConfigurationInterface $configuration, DataSetInterface $dataSet): array
    {
        return [
                'offset' => $configuration->getOffset(),
                'limit' => $configuration->getLimit(),
                'count' => $dataSet->countItems(),
                'filtered' => $dataSet->countItems($grid->getColumns()),
                'actions' => [],
        ];
    }
}
