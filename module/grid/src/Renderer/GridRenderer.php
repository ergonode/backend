<?php

namespace Ergonode\Grid\Renderer;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\GridConfigurationInterface;

/**
 */
class GridRenderer
{
    /**
     * @var ColumnRenderer
     */
    private $columnRenderer;

    /**
     * @var RowRenderer
     */
    private $rowRenderer;

    /**
     * @var InfoRender
     */
    private $infoRenderer;

    /**
     * @param ColumnRenderer $columnRenderer
     * @param RowRenderer    $rowRenderer
     * @param InfoRender     $infoRenderer
     */
    public function __construct(ColumnRenderer $columnRenderer, RowRenderer $rowRenderer, InfoRender $infoRenderer)
    {
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
    public function render(AbstractGrid $grid, GridConfigurationInterface $configuration, DataSetInterface $dataSet, Language $language): array
    {
        $grid->init($configuration, $language);

        $field = $configuration->getField();
        $order = $configuration->getOrder();
        $records = $dataSet->getItems($grid->getColumns(), $configuration->getLimit(), $configuration->getOffset(), $field, $order);
        $result = [];

        $result['configuration'] = $grid->getConfiguration();
        $result['columns'] = $this->columnRenderer->render($grid, []);
        $result['collection'] = [];

        foreach ($records as $row) {
            $result['collection'][] = $this->rowRenderer->render($grid, $row);
        }

        $result['info'] = $this->infoRenderer->render($grid, $configuration, $dataSet);

        return $result;
    }
}
